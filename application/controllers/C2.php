<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class C2 extends CI_Controller
	{

		public function __construct()
		{	
			parent::__construct();	
			$this->load->model('C2_model');
		}

		public function All()
		{

			$this->C2_model->ClearAll();				// 

			$this->GetSuppliers();			// Proveedores
			$this->GetEmployees();			// Empleados
			$this->GetAccountCategories();	// Categorías de clientes
			$this->GetClients(); 			// Clientes
			$this->GetAssets(); 			// Activos
			$this->GetWorkEstimates(); 		// Cotizaciones
			$this->GetWorkOrders(); 		// Ordenes de trabajo
			$this->GetWorkDeliveryNotes(); 	// Notas de entrega
			$this->GetInvoices(); 			// Facturas

			$this->C2_model->GenerateEndTables();
		}
		// End ALL();



		// Obtiene todas las WorkOrders
		public function GetInvoices()
		{
			echo "<pre>";

			$ordinaryInvoices = $this->C2_model->GET( 'ordinaryInvoices', [ "limit" => 500 ], $error );
			if( $ordinaryInvoices !== FALSE )
			{
				$ordinaryInvoices = json_decode( $ordinaryInvoices );
				foreach( $ordinaryInvoices as $ordinaryInvoice )
				{
					$this->C2_model->AddOrdinaryInvoice( $ordinaryInvoice );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetInvoices()


		// Obtiene todas las WorkOrders
		public function GetSuppliers()
		{
			echo "<pre>";

			$suppliers = $this->C2_model->GET( 'suppliers', [ "limit" => 500 ], $error );
			if( $suppliers !== FALSE )
			{
				$suppliers = json_decode( $suppliers );
				foreach( $suppliers as $supplier )
				{
					$this->C2_model->AddSupplier( $supplier );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetInvoices()

		// Obtiene todas las WorkOrders
		public function GetAccountCategories()
		{
			echo "<pre>";

			$accountCategories = $this->C2_model->GET( 'accountCategories', [ "limit" => 500 ], $error );
			if( $accountCategories !== FALSE )
			{
				$accountCategories = json_decode( $accountCategories );
				foreach( $accountCategories as $accountCategory )
				{
					$this->C2_model->AddAccountCategory( $accountCategory );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetAccountCategories()		

		// Obtiene todas las WorkOrders
		public function GetEmployees()
		{
			echo "<pre>";

			$employees = $this->C2_model->GET( 'employees', [ "limit" => 500 ], $error );
			if( $employees !== FALSE )
			{
				$employees = json_decode( $employees );
				foreach( $employees as $employee )
				{
					$this->C2_model->AddEmployee( $employee );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetEmployees()

		// Obtiene todas las WorkOrders
		public function GetWorkDeliveryNotes()
		{
			echo "<pre>";

			$workDeliveryNotes = $this->C2_model->GET( 'workDeliveryNotes', [ "limit" => 500 ], $error );
			if( $workDeliveryNotes !== FALSE )
			{
				$workDeliveryNotes = json_decode( $workDeliveryNotes );
				foreach( $workDeliveryNotes as $workDeliveryNote )
				{
					$this->C2_model->AddWorkDeliveryNote( $workDeliveryNote );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetWorkEstimates()		

		// Obtiene todas las WorkOrders
		public function GetWorkEstimates()
		{
			echo "<pre>";

			$workEstimates = $this->C2_model->GET( 'workEstimates', [ "limit" => 500 ], $error );
			if( $workEstimates !== FALSE )
			{
				$workEstimates = json_decode( $workEstimates );
				foreach( $workEstimates as $workEstimate )
				{
					$this->C2_model->AddWorkEstimate( $workEstimate );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetWorkEstimates()

		// Obtiene todas las WorkOrders
		public function GetWorkOrders()
		{
			echo "<pre>";

			$workOrders = $this->C2_model->GET( 'workOrders', [ "limit" => 500 ], $error );
			if( $workOrders !== FALSE )
			{
				$workOrders = json_decode( $workOrders );
				foreach( $workOrders as $workOrder )
				{
					$this->C2_model->AddWorkOrder( $workOrder );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}
		// End GetWorkOrders()

		public function GetClients()
		{
			echo "<pre>";

			$clients = $this->C2_model->GET( 'clients', [ "limit" => 500 ], $error );
			if( $clients !== FALSE )
			{
				$clients = json_decode( $clients );
				foreach( $clients as $client )
				{
					$this->C2_model->AddClient( $client );
				}
				// End foreach order
			}
			else
			{
				// Error
			}


			$potentialClients = $this->C2_model->GET( 'potentialClients', [ "limit" => 500 ], $error );
			if( $potentialClients !== FALSE )
			{
				$potentialClients = json_decode( $potentialClients );
				foreach( $potentialClients as $potentialClient )
				{
					$this->C2_model->AddClient( $potentialClient );
				}
				// End foreach order
			}
			else
			{
				// Error
			}


		}

		

		public function GetAssets()
		{
			echo "<pre>";

			$assets = $this->C2_model->GET( 'assets', [ "limit" => 500 ], $error );
			if( $assets !== FALSE )
			{
				$assets = json_decode( $assets );
				foreach( $assets as $asset )
				{
					$this->C2_model->AddAsset( $asset );
				}
				// End foreach order
			}
			else
			{
				// Error
			}
		}




	}

?>