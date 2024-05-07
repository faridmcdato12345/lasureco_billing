<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeMasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'emp_id' => $this -> em_id,
            'emp_no' => $this -> em_emp_no,
            'emp_rank_code' => $this -> em_rank_code,
            'emp_basic' => $this -> em_basic,
            'emp_address' => $this -> em_address,
            'emp_civil_status' => $this -> em_civil_status,
            'emp_gender' => $this -> em_gender,
            'emp_birthdate' => $this -> em_birthdate,
            'emp_birthplace' => $this -> em_birthplace,
            'emp_age' => $this -> em_age,
            'emp_blood_type' => $this -> em_blood_type,
            'emp_religion' => $this -> em_religion,
            'emp_citezenship' => $this -> em_citizenship,
            'emp_height_ft' => $this -> em_height_ft,
            'emp_height_inch' => $this -> em_height_inch,
            'emp_weight_lbs' => $this -> em_weight_lbs,
            'emp_home_no' => $this -> em_home_no,
            'emp_mobile_no' => $this -> em_mobile_no,
            'emp_emergency_no' => $this -> em_emergency_no,
            'emp_contact_person' => $this -> em_contact_person,
            'emp_relation' => $this -> em_relation,
            'emp_email_add' => $this -> em_email_add,
            'emp_sss' => $this -> em_sss,
            'emp_tin' => $this -> em_tin,
            'emp_passport' => $this -> em_passport,
            'emp_philhealth' => $this -> em_philhealth,
            'emp_pagibig_mid_' => $this -> em_pagibig_mid_,
            'emp_father_name' => $this -> em_father_name,
            'emp_father_occupation' => $this -> em_father_occ,
            'emp_father_address' => $this -> em_father_addr,
            'emp_mother_name' => $this -> em_mother_name,
            'emp_mother_occupation' => $this -> em_mother_occ,
            'emp_mother_address' => $this -> em_mother_addr,
            'emp_date_hired' => $this -> em_date_hired,
            'emp_full_name' => $this -> em_full_name,
            'emp_postal_code' => $this -> em_postal_code,
            'emp_province' => $this -> em_province,
            'emp_employ_status' => $this -> em_employ_status,
            'emp_mode_of_payroll' => $this -> em_mode_of_payroll,
            'emp_salaryaccount1' => $this -> em_salaryaccount1,
            'emp_bankpayroll' => $this -> em_bankpayroll,
            'emp_spouse_name' => $this -> em_spouse_name,
            'emp_spouse_address' => $this -> em_spouse_address,
            'emp_spouse_occupation' => $this -> em_spouse_occu,
            'emp_path_file' => $this -> em_pathfile,
            'emp_pagibig_rtn_' => $this -> em_pagibig_rtn_,
            'emp_employee_id' => $this -> em_employee_id,
            'emp_position' => $this -> em_position,
            'gas_fnamesname' => $this -> gas_fnamesname,
            'ebs_userid' => $this -> ebs_userid,
            'em_designation' => $this -> ebs_designation,
            'ebs_title' => $this -> ebs_title,
            'ebs_picure' => $this -> ebs_picture,
        ];
    }
}
