<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ledgers extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("ledgers_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('ledgers', $language);	
	}

	public function index() {
		$this->data['ledgers'] = $this->ledgers_m->get_ledgers();
		$this->data["subview"] = "ledgers/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'ledgers', 
					'label' => $this->lang->line("ledgers_name"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_ledgers'
				),
				array(
					'field' => 'note', 
					'label' => $this->lang->line("ledgers_note"), 
					'rules' => 'trim|xss_clean|max_length[200]'
				)
			);
		return $rules;
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "ledgers/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = [
                        "ledgers" => $this->input->post("ledgers"),
                        "note"     => $this->input->post("note"),
                    ];

                $this->ledgers_m->insert_ledgers($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("ledgers/index"));
			}
		} else {
			$this->data["subview"] = "ledgers/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['ledgers'] = $this->ledgers_m->get_ledgers($id);
			if($this->data['ledgers']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "ledgers/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"ledgers" => $this->input->post("ledgers"),
							"note" => $this->input->post("note")
						);

						$this->ledgers_m->update_ledgers($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("ledgers/index"));
					}
				} else {
					$this->data["subview"] = "ledgers/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->ledgers_m->delete_ledgers($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("ledgers/index"));
		} else {
			redirect(base_url("ledgers/index"));
		}
	}

	public function unique_ledgers() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$ledgers = $this->ledgers_m->get_order_by_ledgers(array("ledgers" => $this->input->post("ledgers"), "ledgersID !=" => $id));
			if(customCompute($ledgers)) {
				$this->form_validation->set_message("unique_ledgers", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$monthly = $this->input->post('monthly');
			if($monthly) {
				for($i = 1; $i<=12; $i++) {
                    $month = date('M', mktime(0, 0, 0, $i));
                    $array = [
                        'ledgers' => $this->input->post('ledgers'). ' ['.$month.']'
                    ];
					$ledgers = $this->ledgers_m->get_order_by_ledgers($array);

					if(customCompute($ledgers)) {
						$this->form_validation->set_message("unique_ledgers", "The ".$this->input->post('ledgers'). ' ['.$month.']' ." already exists");
						return FALSE;
					}
                }
				return TRUE;
			} else {
				$ledgers = $this->ledgers_m->get_order_by_ledgers(array("ledgers" => $this->input->post("ledgers")));
				if(customCompute($ledgers)) {
					$this->form_validation->set_message("unique_ledgers", "%s already exists");
					return FALSE;
				}
				return TRUE;
			}
		}	
	}
}
