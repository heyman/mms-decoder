<?php
/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of MMS Decoder Example Application.
 * Download/show a part in an MMS message, retrieved from the parts
 * table in the database.
 *
 * MMS Decoder is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License as
 * published by Affero, Inc.; either version 1 of the License, or
 * (at your option) any later version.
 *
 * MMS Decoder is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License in the COPYING file that comes with The Affero Project; if
 * not, write to Affero, Inc., 510 Third Street, Suite 225, San
 * Francisco, CA 94107 USA. 
 */

require_once('config.php');
require_once('functions.php');

db_connect();

mms_part_dload($_GET['id']);

db_close();


?>