<?php

namespace App\DoctrineExtension;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class Time extends FunctionNode
{
    public $time;

    /**
     * @override
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'TIME('.$sqlWalker->walkArithmeticPrimary($this->time).')';
    }

    /**
     * @override
     *
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->time = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
