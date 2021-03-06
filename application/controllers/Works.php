<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Works extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('PartyData');
		$this->load->model('Art');
		$this->load->model('ArtUserAssociation');
		$this->load->model('ArtVerificationWaitingList');
	}

	public function index()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			$data['artList'] = $this->Art->show_current_works($this->PartyData->getCurrentPartyID());
			if($data['artList'] != NULL)
			{
				$this->load->view('templates/header');
				$this->load->view('templates/henosis');
				$this->load->view('templates/contentStart');
				$this->load->view('templates/headerRow');
				$this->load->view('content/works', $data);
				$this->load->view('templates/contentEnd');
				require_once 'required/menu.php';
				$this->load->view('templates/footer');
			}
			else
			{
				redirect(base_url());
			}
		}
		else
		{
			$this->load->view('errors/not_configured');
		}
	}

	public function mine()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if($this->session->userdata['userData']['id'])
			{
				$artIDs = $this->ArtUserAssociation->getArtIDs($this->session->userdata['userData']['id']);
				if($artIDs != NULL)
				{
					$data['artArray'] = $this->Art->show_current_works_from_given_artID_list($this->PartyData->getCurrentPartyID(), $artIDs);
					if($data['artArray'] != NULL)
					{
						$this->load->view('templates/header');
						$this->load->view('templates/henosis');
						$this->load->view('templates/contentStart');
						$this->load->view('templates/headerRow');
						if($this->ArtVerificationWaitingList->checkForWorkUser($this->PartyData->getCurrentPartyID(), $this->session->userdata['userData']['id']) > 0)
						{
							$this->load->view('content/worksWaitingAlert');
						}
						$this->load->view('content/worksMine', $data);
						$this->load->view('templates/contentEnd');
						require_once 'required/menu.php';
						$this->load->view('templates/footer');
					}
					else if($this->ArtVerificationWaitingList->checkForWorkUser($this->PartyData->getCurrentPartyID(), $this->session->userdata['userData']['id']) > 0)
					{
						$this->load->view('templates/header');
						$this->load->view('templates/henosis');
						$this->load->view('templates/contentStart');
						$this->load->view('templates/headerRow');
						$this->load->view('content/worksWaitingAlert');
						$this->load->view('templates/contentEnd');
						require_once 'required/menu.php';
						$this->load->view('templates/footer');
					}
					else
					{
						redirect(base_url());
					}
				}
				else if($this->ArtVerificationWaitingList->checkForWorkUser($this->PartyData->getCurrentPartyID(), $this->session->userdata['userData']['id']) > 0)
				{
					$this->load->view('templates/header');
					$this->load->view('templates/henosis');
					$this->load->view('templates/contentStart');
					$this->load->view('templates/headerRow');
					$this->load->view('content/worksWaitingAlert');
					$this->load->view('templates/contentEnd');
					require_once 'required/menu.php';
					$this->load->view('templates/footer');
				}
				else
				{
					redirect(base_url());
				}
			}
			else
			{
				redirect(base_url().'/user_authentication');
			}
		}
		else
		{
			$this->load->view('errors/not_configured');
		}
	}

	public function mine_waiting()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if($this->session->userdata['userData']['id'])
			{
				$waitingWorks['artList'] = $this->ArtVerificationWaitingList->getWorksOfUser($this->PartyData->getCurrentPartyID(), $this->session->userdata['userData']['id']);
				if($waitingWorks != NULL)
				{
					$this->load->view('templates/header');
					$this->load->view('templates/henosis');
					$this->load->view('templates/contentStart');
					$this->load->view('templates/headerRow');
					$this->load->view('content/worksWaiting', $waitingWorks);
					$this->load->view('templates/contentEnd');
					require_once 'required/menu.php';
					$this->load->view('templates/footer');
				}
				else
				{
					redirect(base_url());
				}
			}
			else
			{
				redirect(base_url().'/user_authentication');
			}
		}
		else
		{
			$this->load->view('errors/not_configured');
		}
	}
}
