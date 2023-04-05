<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\TypeAppointment;
use App\Entity\Veto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function save(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAppointmentAt(\DateTime $datetimeStart, TypeAppointment $type, Veto $veto): Appointment|null
    {
        // add $duration minutes to the start startDatetime
        $datetimeEnd = (clone $datetimeStart)->add(new \DateInterval("PT{$type->getDuration()}M"));

        // get the appointment if it exists.
        return $this->createQueryBuilder('a')
            ->where('a.veto = :veto')
            ->andWhere('(:dtStart BETWEEN a.startDatetime AND a.dateEnd) OR (:dtEnd BETWEEN a.startDatetime AND a.dateEnd)')
            ->getQuery()
            ->setParameter('veto', $veto)
            ->setParameter('dtStart', $datetimeStart) // define start time.
            ->setParameter('dtEnd', $datetimeEnd) // define end time.
            ->getOneOrNullResult();
    }

    public function findByVetoOnWeek(Veto $veto, int $weekOffset = 0): array
    {
        $start_week = date('Y-m-d', date_modify(new \DateTime('monday this week'), "+{$weekOffset} week")->getTimestamp());
        $end_week = date('Y-m-d', date_modify(new \DateTime('sunday this week'), "+{$weekOffset} week")->getTimestamp());

        return $this->createQueryBuilder('a')
            ->where('a.veto = :veto')
            ->andWhere('a.startDatetime >= :start')
            ->andWhere('a.startDatetime <= :end')
            ->getQuery()
            ->setParameter('veto', $veto)
            ->setParameter('start', $start_week)
            ->setParameter('end', $end_week)
            ->getArrayResult();
    }

    /**
     * Find all appointment on a day for a specific vet.
     */
    public function findAllAppointmentOnDay(Veto $veto, \DateTime $date, bool $getCompleted): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.veto = :vet_id')
            ->andWhere('DATE(a.date) = :date')
            ->orderBy('a.startHour', 'ASC');

        if ($getCompleted) {
            $queryBuilder->andWhere('a.isCompleted = TRUE');
        }

        return $queryBuilder->getQuery()
            ->setParameter('vet_id', $veto->getId())
            ->setParameter('date', $date->format('Y-m-d'))
            ->getArrayResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findAppointmentOnHour(Veto $veto, \DateTime $dateTime): ?Appointment
    {
        return $this->createQueryBuilder('a')
            ->where('a.veto = :vet_id')
            ->andWhere('DATE(a.date) = :date')
            ->andWhere('TIME(:time) BETWEEN TIME(a.startHour) AND TIME(a.endHour)')
            ->getQuery()
            ->setParameter('vet_id', $veto->getId())
            ->setParameter('time', $dateTime->format('H:i:s'))
            ->setParameter('date', $dateTime->format('Y-m-d'))
            ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
    }

//    /**
//     * @throws NonUniqueResultException
//     * @throws NoResultException
//     */
//    public function updateNote(int $appointmentId, string $note): int
//    {
//        return $this->createQueryBuilder('a')
//            ->update()
//            ->set('a.note', ':note')
//            ->where('a.id = :id')
//            ->getQuery()
//            ->setParameter('id', $appointmentId)
//            ->setParameter('note', $note)
//            ->getSingleScalarResult();
//    }
    /**
     * Check if an appointment is already planned at the same time and overlapping.
     *
     * @throws NonUniqueResultException
     */
    public function getFirstAppointmentOverlapping(Appointment $value): ?Appointment
    {
        // calculate the end hour of the appointment (because the endHour is calculated in the entity prePersist (and this can be before))
        $endHour = (clone $value->getStartHour())->add(new \DateInterval("PT{$value->getType()->getDuration()}M"));

        $result = $this->createQueryBuilder('a')
            ->where('DATE(a.date) = DATE(:date)')
            ->andWhere('(TIME(:startHour) BETWEEN TIME(a.startHour) AND (a.endHour)) OR (TIME(:endHour) BETWEEN TIME(a.startHour) AND TIME(a.endHour)) OR (TIME(a.startHour) > TIME(:startHour) AND TIME(a.endHour) < TIME(:endHour))')
            ->andWhere('TIME(:startHour) = TIME(a.startHour)')
            ->andWhere('a.veto = :veto')
            ->getQuery()
            ->setParameter('startHour', $value->getStartHour())
            ->setParameter('endHour', $endHour)
            ->setParameter('date', $value->getDate())
            ->setParameter('veto', $value->getVeto())
            ->execute(hydrationMode: AbstractQuery::HYDRATE_OBJECT);

        return count($result) > 0 ? $result[0] : null;
    }
}
