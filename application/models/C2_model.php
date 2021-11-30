<?php
	#User: c22020
	#Pass: c2_#!!"-c2_#!!"-
	class C2_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		public function ClearAll()
		{
			$this->db->query( "TRUNCATE TABLE stelorder.ordinaryInvoice;" );
			$this->db->query( "TRUNCATE TABLE stelorder.asset;" );
			$this->db->query( "TRUNCATE TABLE stelorder.workDeliveryNote;" );
			$this->db->query( "TRUNCATE TABLE stelorder.workEstimate;" );
			$this->db->query( "TRUNCATE TABLE stelorder.workOrder;" );
			$this->db->query( "TRUNCATE TABLE stelorder.client;" );
			$this->db->query( "TRUNCATE TABLE stelorder.supplier;" );
			$this->db->query( "TRUNCATE TABLE stelorder.employee;" );
			$this->db->query( "TRUNCATE TABLE stelorder.accountCategory;" );
		}

		public function GenerateEndTables()
		{

			



			$s 	= 	"DROP TABLE IF EXISTS stelorder._clientes;";
			$this->db->query( $s );

			$s 	= 	"
						CREATE TABLE stelorder._clientes
						SELECT
							client.fullReference 																AS ReferenciaCliente,
							client.legalName 																	AS Nombrecliente
						FROM stelorder.`client`;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._activos;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._activos
						SELECT
						
							asset.fullReference 																AS Referencia,
							asset.name 																			AS Nombre,
							asset.brand 																		AS Marca,
							asset.model 																		AS Modelo,
							asset.identifier 																	AS Identificador,
							asset.serialNumber 																	AS NumeroDeSerie,
							client.fullReference 																AS ReferenciaCliente,
							client.legalName 																	AS Nombrecliente,
							MAX( IF( workOrder.fullReference LIKE 'REP-%', workOrder.fullReference, NULL ) ) 	AS ReferenciaREP,
							MAX( IF( workOrder.fullReference LIKE 'ENT-%', workOrder.fullReference, NULL ) ) 	AS ReferenciaENT,
							workDeliveryNote.fullReference 														AS ReferenciaBIT
							
						FROM stelorder.asset
						LEFT JOIN stelorder.`client` 					ON `client`.`clientId` 		= 	asset.clientId
						LEFT JOIN stelorder.workOrderAsset 			ON workOrderAsset.assetId 	= 	asset.assetId
						LEFT JOIN stelorder.workOrder 				ON workOrder.workOrderId 	=	workOrderAsset.workOrderId
						LEFT JOIN stelorder.workDeliveryNoteAsset 	ON workDeliveryNoteAsset.assetId = asset.assetId
						LEFT JOIN stelorder.workDeliveryNote 			ON workDeliveryNote.workDeliveryNoteId = workDeliveryNoteAsset.workDeliveryNoteId
						GROUP BY asset.assetId
						;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._recepciones;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._recepciones
						SELECT
							workOrder.fullReference 							AS ReferenciaREP,
							workOrder.date 										AS Fecha,
							client.fullReference 								AS ReferenciaCliente,
							client.legalName 									AS Nombrecliente,
							workOrder.title 									AS Titulo,
							client.province 									AS estado,
							CONCAT( employee.name, ' ', employee.surname ) 			AS CreadoPor,
							accountCategory.name 								AS Familiacliente,
							workOrder.title 									AS ReferenciaCOT
							
						FROM stelorder.workOrder
						LEFT JOIN stelorder.`client` 			ON `client`.`clientId`	= workOrder.clientId
						LEFT JOIN stelorder.accountCategory 	ON accountCategory.accountCategoryId = client.accountCategoryId
						LEFT JOIN stelorder.employee 			ON employee.employeeId 	= workOrder.creatorId
						WHERE workOrder.fullReference LIKE 'REP-%'
						;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._entregas;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._entregas
						SELECT
							workOrder.fullReference 							AS ReferenciaENT,
							workOrder.date 										AS Fecha,
							client.fullReference 								AS ReferenciaCliente,
							client.legalName 									AS Nombrecliente,
							workOrder.title 									AS Titulo,
							client.province 									AS estado,
							CONCAT( employee.name, employee.surname ) 			AS CreadoPor
							
						FROM stelorder.workOrder
						LEFT JOIN stelorder.`client` 			ON `client`.`clientId`	= workOrder.clientId
						LEFT JOIN stelorder.accountCategory 	ON accountCategory.accountCategoryId = client.accountCategoryId
						LEFT JOIN stelorder.employee 			ON employee.employeeId 	= workOrder.creatorId
						WHERE workOrder.fullReference LIKE 'ENT-%'
						;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._bitacora;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._bitacora
						SELECT
							workDeliveryNote.fullReference 																								AS ReferenciaBIT,
							workDeliveryNote.date 																										AS Fecha,
							client.fullReference 																										AS ReferenciaCliente,
							client.legalName 																											AS Nombrecliente,
							workDeliveryNote.title 																										AS Titulo,
							client.province 																											AS estado,
							CONCAT( employee.name, ' ', employee.surname ) 																				AS CreadoPor,
							workDeliveryNote.dueDate 																									AS FechaRealizacion,
							MAX( IF( workDeliveryNoteLine.itemName = 'TEMPERATURA' , workDeliveryNoteLine.itemDescription , NULL ) ) 					AS Temperatura,
							MAX( IF( workDeliveryNoteLine.itemName = 'HUMEDAD RELATIVA' , workDeliveryNoteLine.itemDescription , NULL ) ) 				AS Humedad,
							MAX( IF( workDeliveryNoteLine.itemName = 'PROCEDIMIENTO DE CALIBRACION' , workDeliveryNoteLine.itemDescription , NULL ) ) 	AS Procedimiento,
							workDeliveryNote.comments 																									AS Observaciones
							
						FROM stelorder.workDeliveryNote
						LEFT JOIN stelorder.`client` 				ON `client`.`clientId` = workDeliveryNote.clientId 
						LEFT JOIN stelorder.employee 		 		ON employee.employeeId 	= workDeliveryNote.creatorId
						LEFT JOIN stelorder.workDeliveryNoteLine 	ON workDeliveryNoteLine.workDeliveryNoteId = workDeliveryNote.workDeliveryNoteId
						GROUP BY workDeliveryNote.workDeliveryNoteId
						;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._cotizaciones;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._cotizaciones
						SELECT

							workEstimate.fullReference 							AS ReferenciaCOT,
							workEstimate.date 									AS Fecha,
							client.fullReference 								AS ReferenciaCliente,
							client.legalName 									AS Nombrecliente,
							workEstimate.title 									AS Titulo,
							client.province 									AS Estado,
							CONCAT( employee.name, ' ', employee.surname )		AS CreadoPor,
							workEstimate.discountPercentage 					AS Descuento,
							workEstimate.totalAmount 							AS Total
							-- workEstimateLine.itemBasePrice						as PrecioUnitario,
							-- workEstimateLine.discountPercentage 				as Descuento
							
						FROM stelorder.workEstimate
						-- left join workEstimateLine 		on workEstimateLine.workEstimateId = workEstimate.workEstimateId
						LEFT JOIN stelorder.`client` 				ON `client`.`clientId` = workEstimate.clientId 
						LEFT JOIN stelorder.employee 		 		ON employee.employeeId 	= workEstimate.creatorId
						;
					";
			$this->db->query( $s );

			$s 	= 	"DROP TABLE IF EXISTS stelorder._facturas;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._facturas
						SELECT
						
							ordinaryInvoice.fullReference 										AS ReferenciaFAC,
							ordinaryInvoice.date 												AS Fecha,
							client.legalName 													AS Nombrecliente,
							client.province 													AS Estado,
							CONCAT( employee.name, ' ', employee.surname )						AS CreadoPor,
							GROUP_CONCAT( DISTINCT ordinaryInvoiceLine.itemName ) 				AS ServicioCalibracion,
							GROUP_CONCAT( DISTINCT ordinaryInvoiceLine.itemDescription ) 		AS DescripcionServicio,
							ordinaryInvoice.title 												AS ReferenciaREP
							
						FROM stelorder.ordinaryInvoice
						LEFT JOIN stelorder.ordinaryInvoiceLine 	ON ordinaryInvoiceLine.ordinaryInvoiceId =  ordinaryInvoice.ordinaryInvoiceId
						LEFT JOIN stelorder.`client` 				ON `client`.`clientId` = ordinaryInvoice.clientId 
						LEFT JOIN stelorder.employee 		 		ON employee.employeeId 	= ordinaryInvoice.creatorId
						GROUP BY ordinaryInvoice.ordinaryInvoiceId
						;
					";
			$this->db->query( $s );

			$s 	= 	"DROP TABLE IF EXISTS stelorder._facturasPartidas;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._facturasPartidas
						SELECT	
							ordinaryInvoice.fullReference 				AS ReferenciaFAC,
							ordinaryInvoiceLine.units					AS Unidades,
							ordinaryInvoiceLine.itemName				AS Nombre,
							ordinaryInvoiceLine.itemDescription			AS DEscripcion,
							ordinaryInvoiceLine.itemBasePrice			AS PrecioBase,
							ordinaryInvoiceLine.discountPercentage		AS Descuento,
							ordinaryInvoiceLine.totalAmount				AS Total
						FROM stelorder.ordinaryInvoice
						LEFT JOIN stelorder.ordinaryInvoiceLine ON ordinaryInvoiceLine.ordinaryInvoiceId = ordinaryInvoice.ordinaryInvoiceId
						;
					";
			$this->db->query( $s );


			$s 	= 	"DROP TABLE IF EXISTS stelorder._cotizacionesPartidas;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._cotizacionesPartidas
						SELECT
						
							workEstimate.fullReference 					AS ReferenciaCOT,
							workEstimateLine.units						AS Unidades,
							workEstimateLine.itemName					AS Nombre,
							workEstimateLine.itemDescription			AS DEscripcion,
							workEstimateLine.itemBasePrice				AS PrecioBase,
							workEstimateLine.discountPercentage			AS Descuento,
							workEstimateLine.totalAmount				AS Total
							
						FROM stelorder.workEstimate
						LEFT JOIN stelorder.workEstimateLine ON workEstimateLine.workEstimateId = workEstimate.workEstimateId
						;
					";
			$this->db->query( $s );



			$s 	= 	"DROP TABLE IF EXISTS stelorder._proveedores;";
			$this->db->query( $s );
			
			$s 	= 	"
						CREATE TABLE stelorder._proveedores
						SELECT
							supplier.fullReference AS ReferenciaPROV,
							supplier.name AS Nombre,
							supplier.email AS Email,
							supplier.phone AS Telefono,
							supplier.cityTown AS Ciudad,
							supplier.province AS Estado,
							supplier.taxIdentificationNumber AS RFC
						FROM stelorder.supplier
						;
					";
			$this->db->query( $s );




		}
		// End GenerateEndTables


		
		public function AddOrdinaryInvoice( $ordinaryInvoice )
		{

			$ordinaryInvoiceId 	= 	$ordinaryInvoice->id;
			$reference 			= 	$ordinaryInvoice->reference;
			$fullReference 		= 	((array)$ordinaryInvoice)['full-reference'];
			$date 				= 	$ordinaryInvoice->date;
			$title 				= 	$ordinaryInvoice->title;
			$clientId 			= 	((array)$ordinaryInvoice)['account-id'];
			$creatorId 			= 	((array)$ordinaryInvoice)['creator-id'];
			$totalAmount		= 	((array)$ordinaryInvoice)['total-amount'];

			

			$s 	= 	"
						INSERT INTO stelorder.ordinaryInvoice( ordinaryInvoiceId, title, reference, fullReference, date, clientId, creatorId, totalAmount, date_added )
						VALUE( '{$ordinaryInvoiceId}', '{$title}', '{$reference}', '{$fullReference}', '{$date}', '{$clientId}', '{$creatorId}', '{$totalAmount}', NOW() )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), title = VALUES(title), ordinaryInvoiceId = VALUES(ordinaryInvoiceId), reference = VALUES(reference), fullReference = VALUES(fullReference), date = VALUES(date), clientId = VALUES(clientId), creatorId = VALUES(creatorId), totalAmount = VALUES(totalAmount)
					";

			$this->db->query( $s );


			// Lines

			$this->db->query( "DELETE FROM stelorder.ordinaryInvoiceLine WHERE ordinaryInvoiceId = '{$ordinaryInvoiceId}';" );
			$lines = [];
			foreach( $ordinaryInvoice->lines as $line )
				$lines[] 	= 	[ 
									"ordinaryInvoiceId" 	=> $ordinaryInvoiceId,
									"itemName" 				=> trim( ((array)$line)['item-name'] ),
									"itemDescription" 		=> trim( ((array)$line)['item-description'] ),
									"units" 				=> trim( ((array)$line)['units'] ),
									"itemBasePrice" 		=> trim( ((array)$line)['item-base-price'] ),
									"discountPercentage" 	=> trim( ((array)$line)['discount-percentage'] ),
									"totalAmount" 			=> trim( ((array)$line)['total-amount'] ),
								];

			$s = $this->db->insert_batch( "stelorder.ordinaryInvoiceLine", $lines );


		}
		// End AddOrdinaryInvoice( $ordinaryInvoice )

		public function AddAsset( $asset )
		{

			$assetId 		= 	$asset->id;
			$reference 		= 	$asset->reference;
			$fullReference 	= 	((array)$asset)['full-reference'];
			$name 			= 	$asset->name;
			$brand 			= 	$asset->brand;
			$model 			= 	$asset->model;
			$identifier 	= 	$asset->identifier;
			$serialNumber 	= 	((array)$asset)['serial-number'];
			$clientId 		= 	((array)$asset)['account-id'];

			$s 	= 	"
						INSERT INTO stelorder.asset( assetId, reference, fullReference, name, brand, model, identifier, serialNumber, clientId, date_added )
						VALUE( '{$assetId}', '{$reference}', '{$fullReference}', '{$name}', '{$brand}', '{$model}', '{$identifier}', '{$serialNumber}', '{$clientId}', NOW() )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), name = VALUES( name ), brand = VALUES( brand ), model = VALUES( model ), identifier = VALUES( identifier ), serialNumber = VALUES( serialNumber ), clientId = VALUES( clientId )
					";

			$this->db->query( $s );
		}


		public function AddWorkDeliveryNote( $workDeliveryNote )
		{

			$workDeliveryNoteId	= 	$workDeliveryNote->id;
			$reference 			= 	$workDeliveryNote->reference;
			$title 				= 	$workDeliveryNote->title;
			$comments			= 	$workDeliveryNote->comments;
			$dueDate 			= 	((array)$workDeliveryNote)['due-date'];
			$fullReference 		= 	((array)$workDeliveryNote)['full-reference'];
			$date 				= 	$workDeliveryNote->date;
			$clientId 			= 	((array)$workDeliveryNote)['account-id'];
			$creatorId 			= 	((array)$workDeliveryNote)['creator-id'];


			
			$s 	= 	"
						INSERT INTO stelorder.workDeliveryNote( creatorId, workDeliveryNoteId, reference, fullReference, title, comments, date, clientId, dueDate, date_added )
						VALUE( '{$creatorId}', '{$workDeliveryNoteId}', '{$reference}', '{$fullReference}', '{$title}', '{$comments}', '{$date}', '{$clientId}', '{$dueDate}', NOW() )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), title = VALUES( title ), comments = VALUES( comments ), date = VALUES( date ), clientId = VALUES( clientId ), dueDate = VALUES( dueDate ), creatorId = VALUES( creatorId )
					";
			$this->db->query( $s );


			// Lines

			$this->db->query( "DELETE FROM stelorder.workDeliveryNoteLine WHERE workDeliveryNoteId = '{$workDeliveryNoteId}';" );
			$lines = [];
			foreach( $workDeliveryNote->lines as $line )
				$lines[] 	= 	[ 
									"workDeliveryNoteId" 	=> $workDeliveryNoteId,
									"itemName" 				=> trim( ((array)$line)['item-name'] ),
									"itemDescription" 		=> trim( ((array)$line)['item-description'] ),
								];

			$s = $this->db->insert_batch( "stelorder.workDeliveryNoteLine", $lines );



			// Assets
			$this->db->query( "DELETE FROM stelorder.workDeliveryNoteAsset WHERE workDeliveryNoteId = '{$workDeliveryNoteId}';" );
			$assetIds = [];
			foreach( $workDeliveryNote->assets as $asset )
				$assetIds[] = [ "workDeliveryNoteId" => $workDeliveryNoteId, "assetId" => $asset->id ];
			$s = $this->db->insert_batch( "stelorder.workDeliveryNoteAsset", $assetIds );


		}
		// End function AddWorkDeliveryNote( $workEstimate )


		public function AddWorkEstimate( $workEstimate )
		{

			$workEstimateId 	= 	$workEstimate->id;
			$reference 			= 	$workEstimate->reference;
			$fullReference 		= 	((array)$workEstimate)['full-reference'];
			$date 				= 	$workEstimate->date;
			$clientId 			= 	((array)$workEstimate)['account-id'];


			$title 				= 	$workEstimate->title;
			$creatorId 			= 	((array)$workEstimate)['creator-id'];
			$totalAmount 		= 	((array)$workEstimate)['total-amount'];
			$discountPercentage	= 	((array)$workEstimate)['discount-percentage'];

			

			$s 	= 	"
						INSERT INTO stelorder.workEstimate( workEstimateId, reference, fullReference, date, clientId, date_added, title, creatorId, totalAmount, discountPercentage )
						VALUE( '{$workEstimateId}', '{$reference}', '{$fullReference}', '{$date}', '{$clientId}', NOW(), '{$title}', '{$creatorId}', '{$totalAmount}', '{$discountPercentage}' )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), date = VALUES( date )
						, clientId = VALUES( clientId )
						, title = VALUES( title )
						, creatorId = VALUES( creatorId )
						, totalAmount = VALUES( totalAmount )
						, discountPercentage = VALUES( discountPercentage )
					";

			$this->db->query( $s );

			$this->db->query( "DELETE FROM stelorder.workEstimateLine WHERE workEstimateId = '{$workEstimateId}';" );

			$lines = [];
			foreach( $workEstimate->lines as $line )
				$lines[] 	= 	[ 
									"workEstimateId"		=> $workEstimateId,
									"units" 				=> $line->units,
									"discountPercentage" 	=> ( (array)$line )[ 'discount-percentage' ],
									"totalAmount" 			=> ( (array)$line )[ 'total-amount' ],
									"itemName" 				=> ( (array)$line )[ 'item-name' ],
									"itemDescription" 		=> ( (array)$line )[ 'item-description' ],
									"itemBasePrice" 		=> ( (array)$line )[ 'item-base-price' ],
							 	];

			$s = $this->db->insert_batch( "stelorder.workEstimateLine", $lines );


		}
		// End function AddWorkEstimate( $workEstimate )







		public function AddWorkOrder( $workOrder )
		{

			$workOrderId 		= 	$workOrder->id;
			$reference 			= 	$workOrder->reference;
			$fullReference 		= 	((array)$workOrder)['full-reference'];
			$date 				= 	$workOrder->date;
			$title 				= 	trim( $workOrder->title );
			$clientId 			= 	((array)$workOrder)['account-id'];
			$creatorId 			= 	((array)$workOrder)['creator-id'];

			

			$s 	= 	"
						INSERT INTO stelorder.workOrder( workOrderId, reference, fullReference, date, clientId, title, creatorId, date_added )
						VALUE( '{$workOrderId}', '{$reference}', '{$fullReference}', '{$date}', '{$clientId}', '{$title}', '{$creatorId}', NOW() )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), date = VALUES( date ), clientId = VALUES( clientId ), title = VALUES( title ), creatorId = VALUES( creatorId )
					";

			$this->db->query( $s );

			$this->db->query( "DELETE FROM stelorder.workOrderAsset WHERE workOrderId = '{$workOrderId}';" );

			$assetIds = [];
			foreach( $workOrder->assets as $asset )
				$assetIds[] = [ "workOrderId" => $workOrderId, "assetId" => $asset->id ];

			$s = $this->db->insert_batch( "stelorder.workOrderAsset", $assetIds );


		}
		// End function AddWorkOrder( $workOrder )


		public function AddClient( $client )
		{


			$clientId 			= 	$client->id;
			$reference 			= 	$client->reference;
			$fullReference 		= 	((array)$client)['full-reference'];
			$accountCategoryId	= 	((array)$client)['account-category-id'];
			$cityTown			= 	(   (array)(   (array)$client   )['main-address'] )['city-town'];
			$province 			= 	(   (array)(   (array)$client   )['main-address'] )['province'];
			$legalName 			= 	((array)$client)['legal-name'];

			

			$s 	= 	"
						INSERT INTO stelorder.client( accountCategoryId, clientId, reference, fullReference, legalName, cityTown, province, date_added )
						VALUE( '{$accountCategoryId}', '{$clientId}', '{$reference}', '{$fullReference}', '{$legalName}', '{$cityTown}', '{$province}', NOW() )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), legalName = VALUES( legalName ), cityTown = VALUES( cityTown ), province = VALUES( province ), accountCategoryId = VALUES( accountCategoryId )
					";

			$this->db->query( $s );
		}
		// End function AddWorkOrder( $workOrder )


		public function AddSupplier( $supplier )
		{


			$supplierId					= 	$supplier->id;
			$reference 					= 	$supplier->reference;
			$fullReference 				= 	((array)$supplier)['full-reference'];
			$name 						= 	((array)$supplier)['name'];
			$cityTown					= 	(   (array)(   (array)$supplier   )['main-address'] )['city-town'];
			$province 					= 	(   (array)(   (array)$supplier   )['main-address'] )['province'];
			$email 						= 	((array)$supplier)['email'];
			$phone 						= 	((array)$supplier)['phone'];
			$taxIdentificationNumber 	= 	((array)$supplier)['tax-identification-number'];

			

			$s 	= 	"
						INSERT INTO stelorder.supplier( supplierId, reference, fullReference, name, cityTown, province, date_added, email, phone, taxIdentificationNumber )
						VALUE( '{$supplierId}', '{$reference}', '{$fullReference}', '{$name}', '{$cityTown}', '{$province}', NOW(), '{$email}', '{$phone}', '{$taxIdentificationNumber}' )
						ON DUPLICATE KEY UPDATE date_updated = NOW(), reference = VALUES( reference ), fullReference = VALUES( fullReference ), name = VALUES( name ), cityTown = VALUES( cityTown ), province = VALUES( province ), email = VALUES( email ), phone = VALUES( phone ), taxIdentificationNumber = VALUES( taxIdentificationNumber )
					";

			$this->db->query( $s );
		}


		public function AddEmployee( $employee )
		{


			$employeeId		= 	$employee->id;
			$name 			= 	$employee->name;
			$surname 		= 	$employee->surname;

			

			$s 	= 	"
						INSERT INTO stelorder.employee( employeeId, name, surname )
						VALUE( '{$employeeId}', '{$name}', '{$surname}' )
						ON DUPLICATE KEY UPDATE name = VALUES( name ), surname = VALUES( surname )
					";

			$this->db->query( $s );
		}
		// End function AddWorkOrder( $workOrder )

		
		public function AddAccountCategory( $accountCategory )
		{


			$accountCategoryId	= 	$accountCategory->id;
			$name 				= 	$accountCategory->name;

			

			$s 	= 	"
						INSERT INTO stelorder.accountCategory( accountCategoryId, name )
						VALUE( '{$accountCategoryId}', '{$name}')
						ON DUPLICATE KEY UPDATE name = VALUES( name )
					";

			$this->db->query( $s );
		}
		// End function AddAccountCategory( $accountCategory )


		

		public function WorkOrderByReference( $reference )
		{
			$s = "SELECT * FROM stelorder.workOrder WHERE reference = '{$reference}';";
			$r = $this->db->query( $s );
			if( $r->num_rows() )
				return $r->row();
			else
				return FALSE;
		}
		// End WorkOrderByReference( $reference )





		public function GET( $resource, $query, &$error )
		{
			$token = $this->db->query("SELECT `value` as apikey FROM stelorder._token;")->row()->apikey;

			if( !is_array( $query ) )
			{
				$error = "Query debe de ser un array";
				return FALSE;
			}
			$query['APIKEY'] = $token;

			$query = http_build_query( $query );
			// $header = 	[ 
			// 				'Content-Type: application/json',
			// 				'Content-Length: ' . strlen( $json ),
			// 			];

			$ch = curl_init( "https://app.stelorder.com/app/{$resource}?{$query}");    
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET" );    
		    // curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
		    // curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
		    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		    $result = curl_exec($ch);
		    curl_close($ch);	

		    return $result;		
		}


	}

?>