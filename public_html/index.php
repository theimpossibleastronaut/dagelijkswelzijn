<?php
	/*
		This file serves as the bootstrap process for the DagelijksWelzijn webapplication.

		Sources and data are kept below the public_html folder.
		If this is your first glance at the code, please keep in mind that it's a work in
		progress. This is a project to help people track their health ond help them and
		their professionals make the proper diagnosis. This is however a spare time project.
		Please report any security issues immediatly on the github project.
		https://github.com/theimpossibleastronaut/dagelijkswelzijn
	 */

	require_once( "../source/bootstrap.php" );
	new dw\Controller;
?>