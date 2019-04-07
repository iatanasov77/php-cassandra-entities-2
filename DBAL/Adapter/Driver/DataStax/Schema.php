<?php
namespace VankoSoft\Alexandra\DBAL\Adapter\Driver\DataStax;

class Schema
{
    
    public static function create( $repositories, $keyspaceSchema )
    {
        $schema = [];
        
        foreach( $repositories as $alias => $repoConfig )
        {
            $schema[$alias] = [
                'table'     => $repoConfig['table'],
                'columns'   => []
            ];
            $table     = $keyspaceSchema->table( $repoConfig['table'] );

            foreach ( $table->columns() as $column )
            {
                $schema[$alias]['columns'][$column->name()] = $column->type()->name();
            }
        }
        
        return $schema;
    }
}

