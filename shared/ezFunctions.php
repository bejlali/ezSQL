<?php
/**
 * Author:  Lawrence Stubbs <technoexpressnet@gmail.com>
 *
 * Important: Verify that every feature you use will work with your database vendor.
 * ezSQL Query Builder will attempt to validate the generated SQL according to standards.
 * Any errors will return an boolean false, and you will be responsible for handling.
 *
 * ezQuery does no validation whatsoever if certain features even work with the
 * underlying database vendor. 
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

use ezsql\ezSchema;
use ezsql\ezQuery;
use ezsql\ezQueryInterface;

	// ezQuery prepare placeholder/positional tag
		const _TAG = '__ez__';
    // Use to set get_result output as json 
        const _JSON = 'json';
 
    /*
     * Operator boolean expressions.
     */
		const EQ  = '=';
		const NEQ = '<>';
		const NE  = '!=';
		const LT  = '<';
		const LTE = '<=';
		const GT  = '>';
		const GTE = '>=';
        const _BOOLEAN = ['<', '>', '=', '!=', '>=', '<=', '<>'];
    
		const _IN = 'IN';
		const _notIN = 'NOT IN';
		const _LIKE = 'LIKE';
		const _notLIKE  = 'NOT LIKE';
		const _BETWEEN = 'BETWEEN';
		const _notBETWEEN = 'NOT BETWEEN';
        
		const _isNULL = 'IS NULL';
        const _notNULL  = 'IS NOT NULL';
        const _BOOLEAN_OPERATORS = ['<', '>', '=', '!=', '>=', '<=', '<>', 
            'IN', 'LIKE', 'NOT LIKE', 'BETWEEN', 'NOT BETWEEN', 'IS', 'IS NOT'];
    
    /*
     * Combine operators .
     */    
		const _AND = 'AND';
		const _OR = 'OR';
		const _NOT = 'NOT';
        const _andNOT = 'AND NOT'; 
        const _COMBINERS = ['AND', 'OR', 'NOT', 'AND NOT'];

    /*
     * for joining shortcut methods.
     */    
		const _INNER = 'INNER';
		const _LEFT = 'LEFT';
		const _RIGHT = 'RIGHT';
        const _FULL = 'FULL'; 
        const _JOINERS = ['INNER', 'LEFT', 'RIGHT', 'FULL']; 
        
        /**
        * Associative array of supported SQL Drivers, and library
        * @define(array)
        */
        const VENDOR = [
            'mysql' => 'ez_mysqli',
            'mysqli' => 'ez_mysqli',
            'pdo' => 'ez_pdo',
            'postgresql' => 'ez_pgsql',
            'pgsql' => 'ez_pgsql',
            'sqlite' => 'ez_sqlite3',
            'sqlite3' => 'ez_sqlite3',
            'sqlserver' => 'ez_sqlsrv',
            'mssql' => 'ez_sqlsrv',
            'sqlsrv' => 'ez_sqlsrv'
        ];

        // String SQL data types
        const CHAR = 'CHAR';
        const VARC = 'VARCHAR';
        const VARCHAR = 'VARCHAR';
        const TEXT = 'TEXT';
        const TINY = 'TINYTEXT';
        const TINYTEXT = 'TINYTEXT';
        const MEDIUM = 'MEDIUMTEXT';
        const MEDIUMTEXT = 'MEDIUMTEXT';
        const LONG = 'LONGTEXT';
        const LONGTEXT = 'LONGTEXT';
        const BINARY = 'BINARY';
        const VARBINARY = 'VARBINARY';
        const NCHAR = 'NCHAR';
        const NVAR = 'NVARCHAR';
        const NVARCHAR = 'NVARCHAR';
        const NTEXT = 'NTEXT';
        const IMAGE = 'IMAGE';
        const CLOB = 'CLOB';
        
        // Numeric SQL data types
        const INTS = 'INT';
        const INT2 = 'INT2';
        const INT4 = 'INT4';
        const INT8 = 'INT8';
        const NUMERIC = 'NUMERIC';
        const DECIMAL = 'DECIMAL';
        const BIT = 'BIT';
        const VARBIT = 'VARBIT';
        const INTEGERS = 'INTEGER';
        const TINYINT = 'TINYINT';
        const SMALLINT = 'SMALLINT';
        const MEDIUMINT = 'MEDIUMINT';
        const LARGE = 'BIGINT';
        const BIGINT = 'BIGINT';
        const DEC = 'DEC';
        const FIXED = 'FIXED';
        const FLOATS = 'FLOAT';
        const DOUBLES = 'DOUBLE';
        const REALS = 'REAL';
        const BOOLS = 'BOOL';
        const BOOLEANS = 'BOOLEAN';
        const SMALLMONEY = 'SMALLMONEY';
        const MONEY = 'MONEY';
        const SMALLSERIAL = 'SMALLSERIAL';
        const SERIAL = 'SERIAL';
        const BIGSERIAL = 'BIGSERIAL';
        
        // Date/Time SQL data types	
        const DATES = 'DATE';
        const TIMESTAMP = 'TIMESTAMP';
        const TIMES = 'TIME';
        const DATETIME = 'DATETIME';
        const YEAR = 'YEAR';
        const DATETIME2 = 'DATETIME2';
        const SMALLDATETIME = 'SMALLDATETIME';
        const DATETIMEOFFSET = 'DATETIMEOFFSET';
        
        // Large Object SQL data types
        const TINYBLOB = 'TINYBLOB';
        const BLOB = 'BLOB';
        const MEDIUMBLOB = 'MEDIUMBLOB';
        
        const NULLS = 'NULL';
        const notNULL = 'NOT NULL';

        const CONSTRAINT = 'CONSTRAINT';
        const PRIMARY = 'PRIMARY KEY';
        const FOREIGN = 'FOREIGN KEY';
        const UNIQUE = 'UNIQUE';
        const INDEX = 'INDEX';
	
        // Global class instances, will be used to create and call methods directly.        
        global $ezInstance;
 
    function column(string $column = null, string $type = null, ...$args)
    {
        return ezSchema::column($column, $type, ...$args);
    }

    function primary(string $constraintName, ...$primaryKeys)
    {
        $primary[] = \PRIMARY;
        $primary += $primaryKeys;
        return \column(\CONSTRAINT, $constraintName, ...$primary);
    }

    function foreign(string $constraintName, ...$foreignKeys)
    {
        $foreign[] = \FOREIGN;
        $foreign += $foreignKeys;
        return \column(\CONSTRAINT, $constraintName, ...$foreign);
    }

    function unique(string $constraintName, ...$uniqueKeys)
    {
        $unique[] = \UNIQUE;
        $unique += $uniqueKeys;
        return \column(\CONSTRAINT, $constraintName, ...$unique);
    }

    function index(string $indexName, ...$indexKeys)
    {
        return \column(\INDEX, $indexName, ...$indexKeys);
    }

	/**
     * Creates an array from expressions in the following format
     * @param  strings $x,  - The left expression.
     * @param  strings $operator,   - One of 
     *      '<', '>', '=', '!=', '>=', '<=', '<>', 'IN',, 'NOT IN', 'LIKE', 
     *      'NOT LIKE', 'BETWEEN', 'NOT BETWEEN', 'IS', 'IS NOT', or  the constants above.
     * 
     * @param  strings $y,  - The right expression.
     * @param  strings $and,    - combine additional expressions with,  'AND','OR', 'NOT', 'AND NOT'.
     * @param  strings $args    - for any extras
     *
     * function comparison($x, $operator, $y, $and=null, ...$args)
     *  {
     *          return array($x, $operator, $y, $and, ...$args);
     * }
     * 
     * @return array
     */
    
    /**
     * Creates an equality comparison expression with the given arguments.
     */
    function eq($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \EQ, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a non equality comparison expression with the given arguments.
     */
    function neq($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \NEQ, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates the other non equality comparison expression with the given arguments.
     */
    function ne($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \NE, $y, $and, ...$args);
        return $expression;
    }
    
    /**
     * Creates a lower-than comparison expression with the given arguments.
     */
    function lt($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \LT, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a lower-than-equal comparison expression with the given arguments.
     */
    function lte($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \LTE, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a greater-than comparison expression with the given arguments.
     */
    function gt($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \GT, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a greater-than-equal comparison expression with the given arguments.
     */
    function gte($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \GTE, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates an IS NULL expression with the given arguments.
     */
    function isNull($x, $y = 'null', $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_isNULL, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates an IS NOT NULL expression with the given arguments.
     */
    function isNotNull($x, $y = 'null', $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_notNULL, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a LIKE() comparison expression with the given arguments.
     */
    function like($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_LIKE, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a NOT LIKE() comparison expression with the given arguments.
     */
    function notLike($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_notLIKE, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a IN () comparison expression with the given arguments.
     */
    function in($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_IN, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a NOT IN () comparison expression with the given arguments.
     */
    function notIn($x, $y, $and = null, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_notIN, $y, $and, ...$args);
        return $expression;
    }

    /**
     * Creates a BETWEEN () comparison expression with the given arguments.
     */
    function between($x, $y, $y2, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_BETWEEN,$y, $y2, ...$args);
        return $expression;
    }

    /**
     * Creates a NOT BETWEEN () comparison expression with the given arguments.
     */
    function notBetween($x, $y, $y2, ...$args)
    {
        $expression = array();
        \array_push($expression, $x, \_notBETWEEN, $y, $y2, ...$args);
        return $expression;
    }
    
    /**
    * Using global class instances, setup functions to call class methods directly.
    *
    * @param string| object $ezSQL - representing class 
    *   'mysql', 'mysqli', 'pdo', 'postgres', 'sqlite3', 'sqlsrv'
    *
    * @return boolean - true, or false for error
    */
    function setQuery($ezSQL = '') {
        global $ezInstance;
        $status = false;

        if ($ezSQL instanceOf ezQueryInterface) {
			$ezInstance = $ezSQL;
			$status = true;
		} elseif (\array_key_exists(\strtolower($ezSQL), VENDOR)) {
            if (!empty($GLOBALS['db_'.\strtolower($ezSQL)]))
                $ezInstance = $GLOBALS['db_'.\strtolower($ezSQL)];
            $status = !empty($ezInstance);            
        } elseif (!empty($GLOBALS['ezInstance'])) {
            unset($GLOBALS['ezInstance']);
        }

        return $status;
    }
    
    function setInstance($ezSQL = '') {
        return \setQuery($ezSQL);
    }

    function getInstance() {
        global $ezInstance;

        return $ezInstance;
    }
   
    function cleanInput($string) {
        return ezQuery::clean($string);
    } 

    function select($table = '', $columns = '*', ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->selecting($table, $columns, ...$args) 
            : false;
    } 
    
    function select_into($table, $columns = '*', $old = null, ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->select_into($table, $columns, $old, ...$args) 
            : false;
    } 
    
    function insert_select($totable = '', $columns = '*', $fromTable, $from = '*', ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->insert_select($totable, $columns, $fromTable, $from, ...$args) 
            : false;
    }     
    
    function create_select($table, $from, $old = null, ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->create_select($table, $from, $old, ...$args) 
            : false;
    }  
    
    function where( ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->where( ...$args) 
            : false;
    } 
    
    function groupBy($groupBy) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->groupBy($groupBy) 
            : false;
    } 
    
    function having( ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->having( ...$args) 
            : false;
    }

    function innerJoin(
        $leftTable = '',
        $rightTable = '', 
        $leftColumn = null, 
        $rightColumn = null, 
        $condition = \EQ
    ) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->innerJoin($leftTable, $rightTable, $leftColumn, $rightColumn, $condition) 
            : false;
    }

    function leftJoin(
        $leftTable = '',
        $rightTable = '', 
        $leftColumn = null, 
        $rightColumn = null, 
        $condition = \EQ
    ) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->leftJoin($leftTable, $rightTable, $leftColumn, $rightColumn, $condition) 
            : false;
    }

    function rightJoin(
        $leftTable = '',
        $rightTable = '', 
        $leftColumn = null, 
        $rightColumn = null, 
        $condition = \EQ
    ) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->rightJoin($leftTable, $rightTable, $leftColumn, $rightColumn, $condition) 
            : false;
    }

    function fullJoin(
        $leftTable = '',
        $rightTable = '', 
        $leftColumn = null, 
        $rightColumn = null, 
        $condition = \EQ
    ) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->fullJoin($leftTable, $rightTable, $leftColumn, $rightColumn, $condition) 
            : false;
    }

    function union($table = '', $columnFields = '*', ...$conditions) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->union($table, $columnFields, ...$conditions) 
            : false;
    } 

    function unionAll($table = '', $columnFields = '*', ...$conditions) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->unionAll($table, $columnFields, ...$conditions) 
            : false;
    } 

    function orderBy($orderBy, $order) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->orderBy($orderBy, $order) 
            : false;
    } 

    function limit($numberOf, $offset = null) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->limit($numberOf, $offset) 
            : false;
    } 
    
    function insert($table = '', $keyValue) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->insert($table, $keyValue) 
            : false;
    } 
    
    function update($table = '', $keyValue, ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->update($table, $keyValue, ...$args) 
            : false;
    } 
    
    function delete($table = '', ...$args) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->delete($table, ...$args) 
            : false;
    } 
        
    function replace($table = '', $keyValue) {
        $ezQuery = \getInstance();
        return ($ezQuery instanceOf ezQueryInterface) 
            ? $ezQuery->replace($table, $keyValue) 
            : false;
    }  

    function ezFunctions() {
        return true;
    }
