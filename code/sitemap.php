<?php
/*
 ____  _     _        ___  ____
|  _ \| |__ (_)_ __  / _ \/ ___|
| |_) | '_ \| | '_ \| | | \___ \
|  _ <| | | | | | | | |_| |___) |
|_| \_\_| |_|_|_| |_|\___/|____/

RhinOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2016 by Josep Sanz Campderrós
More information in http://www.saltos.org or info@saltos.org

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
function sitemap($param)
{
	include_once ("dbapp2.php");
	static $type = 'standard';
	static $queries = array ();
	static $locs = array ();
	static $fields = array (
		'loc',
		'priority',
		'lastmod',
		'changefreq'
	);
	$temp = strtok($param, ' ');
	if ($temp == "TYPE")
		$type = strtolower(strtok(' '));
	elseif ($temp == "QUERY") $queries[] = _dbapp2_replace(trim(strtok('')));
	elseif ($temp == "FIELDS") $fields = explode(',', str_replace(' ', '', trim(strtok(''))));
	elseif ($temp == "LOC")
	{
		$loc = array (
			'loc' => strtok(' ')
		);
		while($tmp = strtok(' '))
		{
			if(strtolower($tmp) == 'priority')
				$loc['priority'] = strtok(' ');
			elseif(strtolower($tmp) == 'base')
				$loc['base'] = strtok(' ');
			elseif(strtolower($tmp) == 'lastmod')
				$loc['lastmod'] = strtok(' ');
		}
		$locs[] = $loc;
	}
	elseif ($temp == "PRINT")
	{
		echo_buffer("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
		if (in_array($type, array (
				'standard',
				'google'
			)))
		{
			echo_buffer("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">");
			foreach ($locs as $loc)
				standard_url($loc, $fields);

			foreach ($queries as $query)
			{
				$result = dbQuery($query);
				while ($row = dbFetchRow($result))
					standard_url($row, $fields);
			}
			echo_buffer("</urlset>\n");
		}
	}
}

function standard_url($row, $fields)
{
	$base = isset ($row['base']) ? $row['base'] : '';
	$base = substr($base, 0, 4) == 'http' ? $base : get_base() . $base;
	$base = substr($base, -1) == '/' ? $base : $base . "/";
	echo_buffer("<url>\n");
	foreach ($fields as $field)
	{
		if ($field == 'loc')
		{
			$row[$field] = substr($row[$field], -4) == '.htm' ? $row[$field] :encode($row[$field]) . ".htm";
			$row[$field] = $base . $row[$field];
		}

		if ($field == 'lastmod' && isset ($row[$field]))
		{
			if ($row[$field] == 0 || !strlen($row[$field]))
				unset ($row[$field]);
			elseif (preg_match('/^[0-9]{10}$/', $row[$field])) $row[$field] = date('c', $row[$field]);
		}

		if (isset ($row[$field]))
			echo_buffer("<${field}>${row[$field]}</${field}>\n");
	}
	echo_buffer("</url>\n");
}
?>
