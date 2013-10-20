<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "skeleton_main.php";

class managment extends skeleton_main {
	
	function __construct()
    {
        parent::__construct();
        
        $this->load->model('attendance_model');
        $this->load->library('ebre_escool_ldap');
        
        // Load the language file
        $this->lang->load('managment','catalan');
        $this->load->helper('language');
        
        $this->config->load('managment');
        
	}
	
	protected function _getvar($name){
		if (isset($_GET[$name])) return $_GET[$name];
		else if (isset($_POST[$name])) return $_POST[$name];
		else return false;
	}
	
	public function massive_change_password_print() {
		$group_code=$this->_getvar("group_code");
		$only_students_with_all_data=$this->_getvar("only_students_with_all_data");
		
		if ($group_code) {
			//Obtain groupdn
			$students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
            $all_groups_dns= $this->ebre_escool_ldap->getAllGroupsDNs($students_base_dn);

			$group_dn="";
			if (array_key_exists($group_code,$all_groups_dns))	{
				$group_dn=$all_groups_dns[$group_code];
			}
			if ($group_dn != "") {
				$new_passwords_array=array();
				$all_group_students_dns = $this->ebre_escool_ldap->getAllGroupStudentsDNs($group_dn);
				
				$i=0;
				$number_of_users= count($all_group_students_dns);
				$new_passwords = array();
				$new_passwords= $this->ebre_escool_ldap->propose_passwords($number_of_users);		
				foreach ($all_group_students_dns as $student_key => $student) {
					
					$user_data= $this->ebre_escool_ldap->getEmailAndPhotoData($student);
					if ($user_data == "") {
						echo "<br/>Fatal Error! No enrollment data found for DN: " . $all_group_students_dns[$i];
						exit(1);
					}
					$personal_email = (isset($user_data['highschoolpersonalemail']['0'])) ? $user_data['highschoolpersonalemail']['0'] : "";
					$photo = (isset($user_data['jpegphoto']['0'])) ? $user_data['jpegphoto']['0'] : "";

					$skip=false;
					switch ($only_students_with_all_data) {
						case 1:
							$skip = ( ($personal_email != "") &&  ($photo != "") ) ? false : true;
							break;
						case 2:
							$skip = ( ($personal_email != "")) ? false : true;
							break;
						case 3:
							$skip = ( ($photo != "") ) ? false : true;
							break;
						case 4:
							break;
					}
					
					if (!$skip) {
						//Generate new password
						if (!$this->ebre_escool_ldap->change_password($student,$new_passwords[$i])) {
							show_error("Password not changed correctly!");
						}
					} else {
						unset($all_group_students_dns[$student_key]);
						unset($new_passwords[$student_key]);
					}
					$i++;
				}
				
				$all_group_students_dns = array_values($all_group_students_dns);
				$new_passwords = array_values($new_passwords);
				//echo "<br/>new_passwords:" . print_r($new_passwords) . "<br/>";
				//echo "<br/>all_group_students_dns:" . print_r($all_group_students_dns) . "<br/>";
				//echo "<br/>group_code:" . $group_code . "<br/>";
				//CALL CONTROLLER print_massive_enrollment with arrays				
				$this->session->set_flashdata('all_group_students_dns', $all_group_students_dns);
				$this->session->set_flashdata('new_passwords_array', $new_passwords);
				$this->session->set_flashdata('group_code', $group_code);
				$this->session->set_flashdata('url_after_download', "http://localhost/ebre-escool/index.php/managment/massive_change_password");
				redirect("reports/print_massive_enrollment", 'refresh');
			}
		}
	}
	
	public function lessons($lesson_code=null) {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/jquery-ui.css'));		
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/tooltipster.css'));			
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");						
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.js"));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/ZeroClipboard.js"));				
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/js/jquery.tooltipster.min.js"));		
			
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();

		$data['all_lessons']=null;

		$exists_assignatures_table=$this->config->item('exists_assignatures_table');		

		$data['exists_assignatures_table']=false;
		if ($exists_assignatures_table)		{
			$data['all_lessons']= $this->attendance_model->getAllLessons(true)->result();
			$data['exists_assignatures_table']=true;			                
		}
		else
			$data['all_lessons']= $this->attendance_model->getAllLessons()->result();
		
		$default_lesson_code = $this->config->item('default_group_code');
		if ($lesson_code==null) {
			$lesson_code=$default_lesson_code;
		}
		
		if (isset($lesson_code)) {
			$data['selected_lesson']= urldecode($lesson_code);
		}	else {
			$data['selected_lesson']=$default_lesson_code;
		}

		$this->load->view('managment/lessons',$data);
		
		$this->_load_body_footer();
	}
	
	
	public function users_in_group($group_code=null) {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$default_group_code = $this->config->item('default_group_code');
		if ($group_code==null) {
			$group_code=$default_group_code;
		}
		
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/jquery-ui.css'));		
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/tooltipster.css'));			
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");						
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.js"));
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/ZeroClipboard.js"));				
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/js/jquery.tooltipster.min.js"));		
			
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();
		
		
		$all_groups = $this->attendance_model->get_all_classroom_groups();
		
		$data['all_groups']=$all_groups->result();
				
		if (isset($group_code)) {
			$data['selected_group']= urldecode($group_code);
		}	else {
			$data['selected_group']=$default_group_code;
		}
		
		$students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
		$default_group_dn=$students_base_dn;
		if ($data['selected_group']!="ALL_GROUPS")
			$default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
		
		if ($data['selected_group']=="ALL_GROUPS")
			$data['selected_group_names']= array (lang("all_students_table_title"),"");
		else
			$data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
		
		$data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);

		$this->load->view('managment/users_in_group',$data);
		
		$this->_load_body_footer();	
	}
	
	public function massive_change_password($group_code=null) {
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$default_group_code = $this->config->item('default_group_code');
		if ($group_code==null) {
			$group_code=$default_group_code;
		}
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/jquery-ui.css'));				
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/tooltipster.css'));		
					
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"));			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"));	
			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");					
		
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.js"));	
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/ZeroClipboard.js"));	
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/js/jquery.tooltipster.min.js"));	
		
		$organization = $this->config->item('organization','skeleton_auth');

		$header_data['header_title']=lang("students_of_a_group") . ". " . $organization;
				
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();
		
		$all_groups = $this->attendance_model->get_all_classroom_groups();
		
		$data['all_groups']=$all_groups->result();
		
		if (isset($group_code)) {
			$data['selected_group']= urldecode($group_code);
		}	else {
			$data['selected_group']=$default_group_code;
		}
		
		$students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
		$default_group_dn=$students_base_dn;
		if ($data['selected_group']!="ALL_GROUPS")
			$default_group_dn=$this->ebre_escool_ldap->getGroupDNByGroupCode($data['selected_group']);
		
		if ($data['selected_group']=="ALL_GROUPS")
			$data['selected_group_names']= array (lang("all_students_table_title"),"");
		else
			$data['selected_group_names']= $this->attendance_model->getGroupNamesByGroupCode($data['selected_group']);
		
		$data['all_students_in_group']= $this->ebre_escool_ldap->getAllGroupStudentsInfo($default_group_dn);

		$this->load->view('managment/massive_change_password',$data);
		
		$this->_load_body_footer();	
	}
	
	
	public function index() {
		$this->massive_change_password();
	}
	
	public function statistics_checkings_groups() {
		
		$skeleton_admin_group = $this->config->item('skeleton_admin_group','skeleton_auth');
		if (!$this->skeleton_auth->logged_in())
		{
			//redirect them to the login page
			redirect($this->skeleton_auth->login_page, 'refresh');
		}
		
		$header_data= $this->add_css_to_html_header_data(
			$this->_get_html_header_data(),
			base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			'http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');		
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/css/TableTools.css'));	
		$header_data= $this->add_css_to_html_header_data(
			$header_data,
			base_url('assets/css/tooltipster.css'));	
		//JS
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js"));
			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			"http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js");					
			
		$header_data= $this->add_javascript_to_html_header_data(
			$header_data,
			base_url("assets/grocery_crud/themes/datatables/extras/TableTools/media/js/TableTools.js"));	
			
		$this->_load_html_header($header_data); 
		
		$this->_load_body_header();
		
		$data['all_groups_table_title']=lang("all_groups");
		
		$all_groups = $this->attendance_model->get_all_classroom_groups();
		
		$data['all_groups']=array();
		
		if ($all_groups) {
			$data['all_groups']=$all_groups->result();
		}
		else {
			$this->load->view('managment/statistics_checkings_groups.php',$data);		
			$this->_load_body_footer();	
			return;
		}
		
		$students_base_dn= $this->config->item('students_base_dn','skeleton_auth');
		
		$all_groups_dns= $this->ebre_escool_ldap->getAllGroupsDNs($students_base_dn);
		                
		$all_groups_totals= array();
		foreach ($all_groups_dns as $groupdn) {
			if ($groupdn != ""){
				$group_total = $this->ebre_escool_ldap->getGroupTotals($groupdn);
				$all_groups_totals += array( $groupdn => $group_total);
			}
		}
		$teachers_base_dn= $this->config->item('teachers_base_dn','skeleton_auth');                     		                
		$all_teachers= $this->ebre_escool_ldap->getAllTeachers($teachers_base_dn);
		
			
		foreach ($data['all_groups'] as $group_key => $group) {
			$personname="";
			if (array_key_exists($group->mentorId,$all_teachers))	{
				$personname=$all_teachers[$group->mentorId];
			}		
			$group->mentor_name=$personname;
			
			$group_dn="";
			if (array_key_exists($group->groupCode,$all_groups_dns))	{
				$group_dn=$all_groups_dns[$group->groupCode];
			}
			$group->ldap_dn=$group_dn;
			
			$group_total=0;
			if (array_key_exists($group_dn,$all_groups_totals))	{
				$group_total=$all_groups_totals[$group_dn];
			}
			$group->total_students=$group_total;
		}

		
		$this->load->view('managment/statistics_checkings_groups.php',$data);
		
		$this->_load_body_footer();	
	}
	
	public function statistics_checkings() {
		$this->statistics_checkings_groups();
	}
	
}
