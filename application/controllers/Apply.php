<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Apply extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('PartyData');
		$this->load->model('ArtVerificationWaitingList');
		$this->load->model('ArtVerificationWaitingListClubsAssociation');
	}

	public function index()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if($this->session->userdata('userData'))
			{
				$this->load->view('templates/header');
				$this->load->view('templates/henosis');
				$this->load->view('templates/contentStart');
				$this->load->view('templates/headerRow');
				$this->load->view('content/apply');
				$this->load->view('templates/contentEnd');
				if($this->session->userdata('userData'))
				{
					$this->load->view('templates/loggedInMenu');
				}
				else
				{
					$this->load->view('templates/menu');
				}
				$this->load->view('templates/footer');
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

	public function work()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if($this->session->userdata('userData'))
			{
				$this->load->view('templates/header');
				$this->load->view('templates/henosis');
				$this->load->view('templates/contentStart');
				$this->load->view('templates/headerRow');
				$this->load->view('content/applyWork', array('error1' => ' ', 'error2' => ' '));
				// $this->load->view('content/art_images_upload_form', array('error' => ' ' ));
				$this->load->view('templates/contentEnd');
				if($this->session->userdata('userData'))
				{
					$this->load->view('templates/loggedInMenu');
				}
				else
				{
					$this->load->view('templates/menu');
				}
				$this->load->view('templates/footer');
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

	public function experience()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if($this->session->userdata('userData'))
			{
				$this->load->view('templates/header');
				$this->load->view('templates/henosis');
				$this->load->view('templates/contentStart');
				$this->load->view('templates/headerRow');
				$this->load->view('content/applyExperience');
				$this->load->view('templates/contentEnd');
				if($this->session->userdata('userData'))
				{
					$this->load->view('templates/loggedInMenu');
				}
				else
				{
					$this->load->view('templates/menu');
				}
				$this->load->view('templates/footer');
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

	public function complete()
	{
		if($this->PartyData->checkPartyExists() == 1)
		{
			if (!empty($this->session->userdata['userData']['id']))
			{
				if(!empty($this->input->post("workName")))
				{
					$artID = $this->ArtVerificationWaitingList->nextArtID();
					$error1 = NULL;
					$error2 = NULL;
					$config['upload_path']          = './uploads/images/art/waiting';
					$config['allowed_types']        = 'jpg|png';
					$config['max_size']             = 1000;
					$config['max_width']            = 4000;
					$config['max_height']           = 4000;
					$config['overwrite']            = TRUE;
					$config['file_name']            = $artID."_type1";

					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('userfile1'))
					{
						$error1 = $this->upload->display_errors();
					}
					else
					{
						$data1 = array('upload_data' => $this->upload->data());
					}
					$config['upload_path']          = './uploads/images/art/waiting';
					$config['allowed_types']        = 'jpg|png';
					$config['max_size']             = 2000;
					$config['max_width']            = 4000;
					$config['max_height']           = 4000;
					$config['overwrite']            = TRUE;
					$config['file_name']            = $artID."_type2";

					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload('userfile2'))
					{
						$error2 = $this->upload->display_errors();
					}
					else
					{
						$data2 = array('upload_data' => $this->upload->data());
					}
					$artName = $this->input->post("workName");
					$artShortDescription = $this->input->post("workShortDescription");
					$artLongDescription = $this->input->post("workLongDescription");
					$patronClub = $this->input->post("clubID");
					if($error1 == NULL && $error2 == NULL)
					{
						// work
						$partyID = $this->PartyData->getCurrentPartyID();
						$category = "work";
						$artID = $this->ArtVerificationWaitingList->newArt($partyID, $this->session->userdata['userData']['id'], $artName, $artShortDescription, $artLongDescription, $category, $data1['upload_data']['file_name'], $data2['upload_data']['file_name']);
						if($patronClub != NULL)
						{
							$this->ArtVerificationWaitingListClubsAssociation->newAssociation($artID, $patronClub);
						}
						$this->load->view('templates/header');
						$this->load->view('templates/henosis');
						$this->load->view('templates/contentStart');
						$this->load->view('templates/headerRow');
						$this->load->view('content/applicationRecieved', $data = array('data1' => $data1, 'data2' => $data2));
						$this->load->view('templates/contentEnd');
						if($this->session->userdata('userData'))
						{
							$this->load->view('templates/loggedInMenu');
						}
						else
						{
							$this->load->view('templates/menu');
						}
						$this->load->view('templates/footer');
					}
					else
					{
						$this->load->view('templates/header');
						$this->load->view('templates/henosis');
						$this->load->view('templates/contentStart');
						$this->load->view('templates/headerRow');
						$this->load->view('content/applyWork', $error = array('error1' => $error1, 'error2' => $error2, 'artName'=>$artName, 'artShortDescription'=>$artShortDescription, 'artLongDescription'=>$artLongDescription, 'patronClub'=>$patronClub));
						$this->load->view('templates/contentEnd');
						if($this->session->userdata('userData'))
						{
							$this->load->view('templates/loggedInMenu');
						}
						else
						{
							$this->load->view('templates/menu');
						}
						$this->load->view('templates/footer');
					}
				}
				else if(!empty($this->input->post("experienceName")))
				{
					// experience
					$artName = $this->input->post("experienceName");
					$artShortDescription = $this->input->post("experienceShortDescription");
					$artLongDescription = $this->input->post("experienceLongDescription");
					$partyID = $this->PartyData->getCurrentPartyID();
					$patronClub = $this->input->post("clubID");
					$category = "experience";
					$artID = $this->ArtVerificationWaitingList->newArt($partyID, $this->session->userdata['userData']['id'], $artName, $artShortDescription, $artLongDescription, $category);
					if($patronClub != NULL)
					{
						$this->ArtVerificationWaitingListClubsAssociation->newAssociation($artID, $patronClub);
					}
					$this->load->view('templates/header');
					$this->load->view('templates/henosis');
					$this->load->view('templates/contentStart');
					$this->load->view('templates/headerRow');
					$this->load->view('content/applicationRecieved');
					$this->load->view('templates/contentEnd');
					if($this->session->userdata('userData'))
					{
						$this->load->view('templates/loggedInMenu');
					}
					else
					{
						$this->load->view('templates/menu');
					}
					$this->load->view('templates/footer');
				}
				else
				{
					redirect('apply');
				}
			}
			else
			{
				redirect('user_authentication');
			}
		}
		else
		{
			$this->load->view('errors/not_configured');
		}
	}
}
