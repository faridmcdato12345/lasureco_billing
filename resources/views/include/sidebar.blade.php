<style>
    .dropdown-btn1{
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        /* font-size: 100px; */
        color: black;
        font-weight: normal;
        display: block;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        outline: none;
        text-decoration: none !important;
    }
    .dropdown-btn1:hover{
        background-color: gray;
    }
    .dropdown-btn1.active1{
        color: rgb(23, 108, 191);
    }
</style>
<div class="content2">
    <table border=1 class="bodytable">
        <tr>
            <td class="sidnav-td" style="width: 50%;">
                <div class="sidenav">
                    <div class="main-menu">
                        <!-- <div onclick="location.href='{{url('/dashboard')}}'"><i class="fas fa-chart-line" style="color:#c1cad6"></i><span>DASHBOARD</span></div> -->
                        <div class="menu-transaction"><i class="fas fa-exchange-alt" style="color:#d4adcf"></i><span>TRANSACTION</span></div>
                        <div class="menu-report"><i class="fas fa-file-alt" style="color:#f26a8d"></i><span>REPORT</span></div>
                        <div class="menu-maintenance"><i class="fas fa-wrench" style="color:#f49cbb"></i><span>MAINTENANCE</span></div>
                        <div class="menu-inquiry"><i class="fas fa-question-circle" style="color:#cbeef3"></i><span>INQUIRY</span></div>
                        @if(Auth::user()->hasRole('Admin'))
                        <div class="menu-utility"><i class="fas fa-toolbox"></i><span>UTILITY</span></div>
                        @endif
                    </div>
                    <div class="dropdown-container submenu-transaction submenu">
                        <button style = "font-size: 15px;" href="#" class="dropdown-btn">METER READING
                            <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('print_reading_sheet'))
                            <a href="{{url('user/transaction/meter_reading/print_meter_reading_sheet')}}" class="side-menu" >Print Reading Sheet</a>
                            @endif
                            @if(auth()->user()->can('list_of_receiving_of_soa'))
                            <a href="{{url('user/transaction/meter_reading/list_of_receiving_of_sao')}}" class="side-menu" >List of Receiving of SOA</a>
                            @endif
                            @if(auth()->user()->can('sao_of_acknowledgement_receipt'))
                            <a href="{{url('user/transaction/meter_reading/sao_acknowledgement_receipt')}}" class="side-menu" >SOA of Acknowledgement Receipt</a>
                            @endif
                            @if(auth()->user()->can('renumber_consumer_sequence'))
                            <a href="{{url('user/transaction/meter_reading/renumber_consumer_sequence')}}" class="side-menu" >Renumber Consumer Sequence</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">BILLING
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('enter_meter_readings'))
                            <a href="{{url('user/transaction/billing/enter_meter_readings')}}" class="side-menu">Enter Meter Readings</a>
                            @endif
                            @if(auth()->user()->can('print_billing_edit_list'))
                            <a href="{{url('user/transaction/billing/print_billing_edit_list')}}" class="side-menu">Print Billing Edit List</a>
                            @endif
                            @if(auth()->user()->can('power_bill_adjustments'))
                            <a href="{{url('user/transaction/billing/power_bill_adjustments')}}" class="side-menu">Power Bill Adjustments</a>
                            @endif
                            @if(auth()->user()->can('print_bill'))
                            <a href="{{url('user/transaction/billing/print_new_bill')}}" class="side-menu">Print Bill</a>
                            @endif
                            @if(auth()->user()->can('re_print_bill'))
                            <a href="{{url('user/transaction/billing/re_print_bill')}}" class="side-menu">Re-Print Bill</a>
                            @endif
                            <!-- @if(auth()->user()->can('streetlights_maintenance'))
                            <a href="{{url('user/transaction/billing/street_lights_maintenance')}}" class="side-menu">Streetlights Maintenance</a>
                            @endif -->
                            @if(auth()->user()->can('d_e_of_streetlight'))
                            <a href="{{url('user/transaction/billing/de_of_streetlight_inventory')}}" class="side-menu">D/E of Streetlight Inventory</a>
                            @endif
                            @if(auth()->user()->can('d_e_of_integrated_charges'))
                            <a href="{{url('user/transaction/billing/de_of_integrated_charges')}}" class="side-menu">D/E of Intergrated Charges</a>
                            @endif
                            @if(auth()->user()->can('bill_cancellation'))
                            <a href="{{url('user/transaction/billing/bill_cancellation')}}" class="side-menu">Bill Cancellation</a>
                            @endif
                            @if(auth()->user()->can('summary_of_cancelled_bills'))
                            <a href="{{url('user/transaction/billing/summary_of_cancel_bill')}}" class="side-menu">Summary of Cancelled Bills</a>
                            @endif
                            @if(auth()->user()->can('advance_payment_survey'))
                            <a href="{{url('user/transaction/billing/advance_payment_survey')}}" class="side-menu">Double Payment Entry</a>
                            @endif
                            <!-- @if(auth()->user()->can('cancel_modify_integration'))
                            <a href="{{url('user/transaction/billing/cancel_modify_integration')}}" class="side-menu">Cancel/Modify Integration</a>
                            @endif -->
                            @if(auth()->user()->can('account_receivables_entry'))
                            <a href="{{url('user/transaction/billing/account_receivable_entry')}}" class="side-menu">Account Receivables Entry</a>
                            @endif
                            @if(auth()->user()->can('update_consumer_master'))
                            <a href="#">Update Consumer Master</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">COLLECTION
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('collection_for_teller_pb'))
                            <a href="{{url('user/transaction/collection/collection_for_teller_pb')}}" class="side-menu">Collection for Teller - PB</a>
                            @endif
                            <!-- @if(auth()->user()->can('collection_for_teller_nb'))
                            <a href="{{url('user/transaction/collection/collection_for_teller_nb')}}" class="side-menu">Collection for Teller - NB</a>
                            @endif -->
                            <!-- @if(auth()->user()->can('collection_for_teller_pb'))
                            <a href="{{url('user/transaction/collection/tellers_dcr')}}" class="side-menu">Teller's DCR</a>
                            @endif -->
                            <!-- @if(auth()->user()->can('collection_for_multiple_accounts'))
                            <a href="{{url('user/transaction/collection/collection_for_multiple_accounts')}}" class="side-menu">Collection for Multiple Accounts</a>
                            @endif -->
                            @if(auth()->user()->can('summary_of_posted_non_bill_collection'))
                            <a href="{{url('user/transaction/collection/summary_of_posted_non_bill_collection')}}" class="side-menu">Summary of Posted Non-Bill Collection</a>
                            @endif
                            @if(auth()->user()->can('print_tellers_dcr'))
                            <a href="{{url('user/transaction/collection/print_tellers_daily_collection_report')}}" class="side-menu">Print Tellers DCR</a>
                            @endif
                            @if(auth()->user()->can('print_tellers_summary_dcr'))
                            <a href="{{url('user/transaction/collection/print_summary_of_dcr_teller')}}" class="side-menu">Print Tellers Summary DCR</a>
                            @endif
                            @if(auth()->user()->can('post_tellers_collection'))
                            <a href="{{url('user/transaction/collection/post_teller_collection')}}">Post Tellers Collection</a>
                            @endif
                            @if(auth()->user()->can('void_tellers_collection'))
                            <a href="{{url('user/transaction/collection/void_tellers_collection')}}" class="side-menu">Void Tellers Collection</a>
                            @endif
                            @if(auth()->user()->can('unposted_collection'))
                            <a href="{{url('user/transaction/collection/unposted_collection')}}" class="side-menu">Unposted Collection</a>
                            @endif
							@if(auth()->user()->can('posted_collection_report'))
                            <a href="{{url('user/transaction/collection/posted_collection')}}" class="side-menu">Posted Collection</a>
                            @endif
                            @if(auth()->user()->can('change_acknowledgement_receipt'))
                            <a href="{{url('user/transaction/collection/change_acknowledgement_receipt')}}" class="side-menu">Change Acknowledgement Receipt</a>
                            @endif
                            @if(auth()->user()->can('print_tellers_dcr_nb'))
                            <a href="{{url('user/transaction/collection/print_tellers_non_bill_dcr')}}" class="side-menu">Print Tellers DCR - NB</a>
                            @endif
                            <!-- @if(auth()->user()->can('cashiers_dcr'))
                            <a href="{{url('user/transaction/collection/cashier_dcr')}}" class="side-menu">Cashiers DCR</a>
                            @endif -->
                            <!-- @if(auth()->user()->can('daily_collection_control_list'))
                            <a href="{{url('user/transaction/collection/daily_collection_control_list')}}" class="side-menu">Daily Collection Control List</a>
                            @endif -->
                            @if(auth()->user()->can('summary_of_voided_or'))
                            <a href="{{url('user/transaction/collection/summary_of_voided_or')}}" class="side-menu">Summary of Voided O.R.</a>
                            @endif
                            <!-- @if(auth()->user()->can('summary_of_double_payments'))
                            <a href="{{url('user/transaction/collection/summary_of_double_payment')}}" class="side-menu">Summary of Double Payments</a>
                            @endif -->
                            <!-- @if(auth()->user()->can('locate_payor_account'))
                            <a href="{{url('user/transaction/collection/locate_payor')}}" class="side-menu">Locate Payor Account</a>
                            @endif -->
                            @if(auth()->user()->can('one_time_payment'))
                            <a href="{{url('user/transaction/collection/one_time_payment')}}" class="side-menu">One Time Payment</a>
                            @endif
                            @if(auth()->user()->can('transfter_collection'))
                            <a href="{{url('user/transaction/collection/transfer_collection')}}" class="side-menu">Transfer Collection</a>
                            @endif
                            @if(auth()->user()->can('locatte_or_details'))
                            <a href="{{url('user/transaction/collection/locate_or_details')}}" class="side-menu">Locate OR Details</a>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-container2 submenu-report submenu">
                        <button style="font-size: 15px;" class="dropdown-btn">METER READING
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            {{-- @if(auth()->user()->can('reading_schedule_by_billing_period'))
                            <a href="{{url('user/report/meter_reading/reading_schedule_by_billing_period')}}" class="side-menu">Reading Schedule by Billing Period</a>
                            @endif
                            @if(auth()->user()->can('reading_schedule_by_reading_date'))
                            <a href="{{url('user/report/meter_reading/reading_schedule_by_reading_date')}}" class="side-menu">Reading Schedule by Reading Date</a>
                            @endif
                            @if(auth()->user()->can('disconnected_w_bill_by_billing_period'))
                            <a href="{{url('user/report/meter_reading/disconnected_with_bill_by_billing_period')}}" class="side-menu">Disconnected w/ Bill by Billing Period</a>
                            @endif
                            @if(auth()->user()->can('disconnected_with_bill_per_route'))
                            <a href="{{url('user/report/meter_reading/disconnected_with_bill_per_route')}}" class="side-menu">Disconnected with Bill Per Route</a>
                            @endif
                            @if(auth()->user()->can('cons_applied_w_ave_consumption_by_bp'))
                            <a href="{{url('user/report/meter_reading/cons_applied_with_average_consumption_by_bp')}}" class="side-menu">Cons Applied w/ Ave.Consumption by BP</a>
                            @endif
                            @if(auth()->user()->can('cons_applied_w_ave_consumption_by_mrd'))
                            <a href="{{url('user/report/meter_reading/cons_applied_with_ave_consumption_by_mrd')}}" class="side-menu">Cons Applied w/ Ave.Consumption by MRD</a>
                            @endif
                            @if(auth()->user()->can('unread_unbilled_cons_per_route_mrd'))
                            <a href="{{url('user/report/meter_reading/unread_unbilled_cons_per_route')}}" class="side-menu">Unread/Unbilled Cons per Route/MRD</a>
                            @endif
                            @if(auth()->user()->can('unread_unbilled_cons_per_town_mrd'))
                            <a href="{{url('user/report/meter_reading/unread_unbilled_cons_per_town')}}" class="side-menu">Unread/Unbilled Cons per Town/MRD</a>
                            @endif
                            @if(auth()->user()->can('cons_w_ff_lasureco_month_by_ff'))
                            <a href="{{url('user/report/meter_reading/cons_with_ff__lasureco_month_by_ff')}}" class="side-menu">Cons w/FF LASURECO/Month by FF</a>
                            @endif
                            @if(auth()->user()->can('cons_w_ff_town_month'))
                            <a href="{{url('user/report/meter_reading/cons_with_ff_town_month')}}" class="side-menu">Cons w/FF/Town/Month</a>
                            @endif
                            @if(auth()->user()->can('cons_w_zero_kwh_town_month'))
                            <a href="{{url('user/report/meter_reading/cons_with_zero_kwh_town_month')}}" class="side-menu">Cons w/Zero Kwh/Town/Month</a>
                            @endif
                            @if(auth()->user()->can('time_in_motion_report'))
                            <a href="{{url('user/report/meter_reading/time_in_motion_report')}}" class="side-menu">Time in Motion Report</a>
                            @endif
                            @if(auth()->user()->can('summary_of_field_findings'))
                            <a href="{{url('user/report/meter_reading/summary_of_field_findings')}}" class="side-menu">Summary of Field Findings</a>
                            @endif --}}
                            {{-- <a href="#">Uploaded Routes</a> --}}
                            @if(auth()->user()->can('mrr_inquiry_1'))
                            <a href="{{url('user/report/meter_reading/mrr_1')}}" class="side-menu">MRR Inquiry - 1</a>
                            @endif
                            {{-- @if(auth()->user()->can('mrr_inquiery_2'))
                            <a href="{{url('user/report/meter_reading/mrr_2')}}" class="side-menu">MRR Inquiry - 2</a>
                            @endif
                            @if(auth()->user()->can('mrr_inquiry_per_consumer_1'))
                            <a href="{{url('user/report/meter_reading/mrr_inquiry_per_consumer_1')}}" class="side-menu">MRR Inquiry per Consumer - 1</a>
                            @endif --}}
                            @if(auth()->user()->can('mrr_inquiry_per_consumer_2'))
                            <a href="{{url('user/report/meter_reading/mrr_inquiry_per_consumer_2')}}" class="side-menu">MRR Inquiry per Consumer - 2</a>
                            @endif
                            {{-- <a href="#">List of Cancelled Readings/Meter Reader</a> --}}
                            @if(auth()->user()->can('meter_reading_activity_report'))
                                <a href="{{url('user/report/meter_reading/meter_reading_activity_report')}}" class="side-menu">Meter Reading Activity Report</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">BILLING
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('general_detail_report'))
                            <a href="{{url('user/report/billing/general_detail_report')}}" class="side-menu">General Detail Report</a>  
                            <a href="{{url('user/report/billing/ledger_report')}}" class="side-menu">Ledger Report</a>
                            @endif
                            @if(auth()->user()->can('general_summary_report'))
                            <a href="{{url('user/report/billing/general_summary_report')}}" class="side-menu">General Summary Report</a>
                            @endif
                            @if(auth()->user()->can('power_sales_per_route'))
                            <a href="{{url('user/report/billing/power_sales_per_route')}}" class="side-menu">Power Sales per Route</a>
                            @endif
                            @if(auth()->user()->can('summary_of_sales_unbundled'))
                            <a href="{{url('user/report/billing/summary_of_sales_unbundled')}}" class="side-menu">Summary of Sales Unbundled</a>
                            @endif
                            @if(auth()->user()->can('summary_of_bill_adjustments'))
                            <a href="{{url('user/report/billing/summary_of_bill_adjustments')}}" class="side-menu">Summary of Bill Adjustments</a>
                            @endif
                            @if(auth()->user()->can('adjust_details'))
                            <a href="{{url('user/report/billing/summary_of_bill_adjustments_detailed')}}" class="side-menu">Adjust Details</a>
                            @endif
                            @if(auth()->user()->can('monthly_summary_of_adjustments_dti'))
                            <a href="{{url('user/report/billing/monthly_summary_of_adjustment_dtl')}}" class="side-menu">Monthly Summary of Adjustments - DTI</a>
                            @endif
                            @if(auth()->user()->can('summary_of_bills'))
                            <a href="{{url('user/report/billing/summary_of_bills')}}" class="side-menu">Summary of Bills</a>
                            @endif
                            @if(auth()->user()->can('summary_of_bills_additional'))
                            <a href="{{url('user/report/billing/summary_of_bills_additional')}}" class="side-menu">Summary of Bills - Additional</a>
                            @endif
                            @if(auth()->user()->can('list_of_member_consumer_per_kwh_used'))
                            <a href="{{url('user/report/billing/list_of_member_consumer_per_kwh_used')}}" class="side-menu">List of Member/Consumer per kwh used</a>
                            @endif
                            {{-- @if(auth()->user()->can('summary_of_bills_bapa'))
                            <a href="{{url('user/report/billing/summary_of_bills_bapa')}}" class="side-menu">Summary of Bills - BAPA</a>
                            @endif --}}
                            @if(auth()->user()->can('summary_of_bills_unbundled'))
                            <a href="{{url('user/report/billing/summary_of_bills_unbundled')}}" class="side-menu">Summary of Bills - Unbundled</a>
                            @endif
                            {{-- @if(auth()->user()->can('summary_of_additional_bills'))
                            <a href="{{url('user/report/billing/summary_of_additional_bills')}}" class="side-menu">Summary of Additional Bills</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('frequency_distribution_per_town'))
                            <a href="{{url('user/report/billing/Frequency_Distribution_of_KWH_Consumption')}}" class="side-menu">Frequency Distribution per Town</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('frequency_distribution_consolidated'))
                            <a href="{{url('user/report/billing/frequency_distribution_of_kwh_consumption_consolidated')}}" class="side-menu">Frequency Distribution(Consolidated)</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('list_of_consumer_with_subsidy'))
                            <a href="{{url('user/report/billing/list_of_consumer_with_subsidy')}}" class="side-menu">List of Consumer with Subsidy</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_subsidy_applied_per_town'))
                            <a href="{{url('user/report/billing/summary_of_subsidy_applied')}}" class="side-menu">Summary of Subsidy Applied per Town</a>
                            @endif --}}
                            @if(auth()->user()->can('advance_pb_payment_applied_to_bill'))
                            <a href="{{url('user/report/billing/advance_power_bill_payment_applied_to_bill')}}" class="side-menu">Advance PB Payment Applied to Bill</a>
                            @endif
                            {{-- @if(auth()->user()->can('print_consumption_by_bracket_per_route'))
                            <a href="{{url('user/report/billing/print_consumption_by_bracket_per_route')}}" class="side-menu">Print Consumption by Bracket per Route</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('print_consumption_by_bracket_per_town'))
                            <a href="{{url('user/report/billing/print_consumption_by_bracket_per_town')}}" class="side-menu">Print Consumption by Bracket per Town</a>
                            @endif --}}
                            @if(auth()->user()->can('lifeline_consumer_detailed'))
                            <a href="{{url('user/report/billing/lifeline_consumer_detailed')}}" class="side-menu">Lifeline Consumer Detailed</a>
                            @endif
                            @if(auth()->user()->can('summary_of_unread_unbilled_consumers'))
                            <a href="{{url('user/report/billing/summary_of_unread_unbilled_consumer')}}" class="side-menu">Summary of Unread/Unbilled Consumers</a>
                            @endif
                            @if(auth()->user()->can('summary_of_consumer_per_kwh_used'))
                            <a href="{{url('user/report/billing/summary_of_consumer_per_kwh_used')}}" class="side-menu">Summary of Consumer per KWH Used</a>
                            @endif
                            {{-- @if(auth()->user()->can('lifeline_discount_subsidy'))
                            <a href="{{url('user/report/billing/lifeline_discount_subsidy')}}" class="side-menu">Lifeline Discount/Subsidy</a>
                            @endif --}}
                            @if(auth()->user()->can('sales_per_type_with_consumer_name'))
                            <a href="{{url('user/report/billing/sales_per_type_with_consumer_name')}}" class="side-menu">Sales per Type with Consumer Name</a>
                            @endif
                            {{-- @if(auth()->user()->can('sales_rental_with_cosumer_name'))
                            <a href="{{url('user/report/billing/sales_rental_with_consumer_name')}}" class="side-menu">Sales Rental with Consumer Name</a>
                            @endif --}}
                            @if(auth()->user()->can('consumer_data'))
                            <a href="{{url('user/report/billing/consumer_data')}}" class="side-menu">Consumer Data</a>
                            @endif
                            {{-- @if(auth()->user()->can('list_of_consumer_not_posted_to_ledger'))
                            <a href="{{url('user/report/billing/list_of_consumer_not_posted_to_ledger')}}" class="side-menu">List of Consumer Not Posted to Ledger</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_refunded_bill_deposits'))
                            <a href="{{url('user/report/billing/summary_of_refunded_bill_deposits')}}" class="side-menu">Summary of Refunded Bill Deposits</a>
                            @endif --}}
                            @if(auth()->user()->can('summary_of_bills_amounted_issued'))
                            <a href="{{url('user/report/billing/summary_of_bills_amounted_issued')}}" class="side-menu">Summary of Bills - Amounted Issued</a>
                            @endif
                            {{-- @if(auth()->user()->can('summary_of_bapa_descount_fare'))
                            <a href="{{url('user/report/billing/summary_of_bapa_discount_fare')}}" class="side-menu">Summary of BAPA Discount/Fare</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('large_load_per_route'))
                            <a href="{{url('user/report/billing/large_load_per_route')}}" class="side-menu">Large Load Per Route</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('accounting_of_power_bills'))
                            <a href="{{url('user/report/billing/accounting_of_power_bills')}}" class="side-menu">Accounting of Power Bills</a>
                            @endif --}}
                            @if(auth()->user()->can('summary_of_sales_coverage'))
                            <a href="{{url('user/report/billing/summary_of_sales_coverage')}}" class="side-menu">Summary of Sales - Coverage</a>
                            @endif
                            @if(auth()->user()->can('summary_of_energy_sales_per_cons_type'))
                            <a href="{{url('user/report/billing/summary_of_energy_sales_per_consumer_type')}}" class="side-menu">Summary of Energy Sales per Cons Type</a>
                            @endif
                            {{-- @if(auth()->user()->can('sr_citizens_discount_detailed'))
                            <a href="{{url('user/report/billing/senior_citizens_with_discount_detailed')}}" class="side-menu">SR Citizens Discount - Detailed</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('sr_citizens_discount'))
                            <a href="{{url('user/report/billing/senior_citizens_discount')}}" class="side-menu">SR Citizens Discount</a>
                            @endif
                            @if(auth()->user()->can('additional_bills_previous_months'))
                            <a href="{{url('user/report/billing/additional_bills_previous_month')}}" class="side-menu">Additional Bills - Previous Months</a>
                            @endif --}}
                            @if(auth()->user()->can('summary_of_sales_large_loads'))
                            <a href="{{url('user/report/billing/summary_of_sales_big_load')}}" class="side-menu">Summary of Sales - Large Loads</a>
                            @endif
                            @if(auth()->user()->can('summary_of_revenue_for_month_town'))
                            <a href="{{url('user/report/billing/summary_of_revenue_town')}}" class="side-menu">Summary of Revenue for Month/Town</a>
                            @endif
                            @if(auth()->user()->can('summary_of_revenue_for_month_town'))
                            <a href="{{url('user/report/billing/summary_of_revenue_consumer_type')}}" class="side-menu">Summary of Revenue for Month/ConsType</a>
                            @endif
                            {{-- @if(auth()->user()->can('sr_citizends_discount_per_level'))
                            <a href="{{url('user/report/billing/senior_citizens_discount_per_level')}}" class="side-menu">SR Citizens Discount per Level</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('sr_citizens_age_contact'))
                            <a href="{{url('user/report/billing/senior_citizens_age_contract')}}" class="side-menu">SR Citizens Age Contract</a>
                            @endif --}}
                            @if(auth()->user()->can('lifeline_discount_per_area'))
                            <a href="{{url('user/report/billing/lifeline_discount_per_area')}}" class="side-menu">Lifeline Discount per Area</a>
                            @endif
                            @if(auth()->user()->can('lifeline_discount_per_town'))
                            <a href="{{url('user/report/billing/lifeline_discount_per_town')}}" class="side-menu">Lifeline Discount per Town</a>
                            @endif
                            @if(auth()->user()->can('lifeline_discount_per_route'))
                            <a href="{{url('user/report/billing/lifeline_discount_per_route')}}" class="side-menu">Lifeline Discount per Route</a>
                            @endif
                            @if(auth()->user()->can('monthly_summary_of_lifeline'))
                            <a href="{{url('user/report/billing/monthly_summary_lifeline_consumer')}}" class="side-menu">Monthly Summary of Lifeline</a>
                            @endif
                            @if(auth()->user()->can('lifeline_cons_detail_per_town'))
                            <a href="{{url('user/report/billing/lifeline_consumer_detailed_per_town')}}" class="side-menu">Lifeline Cons Detail per Town</a>
                            @endif
                            @if(auth()->user()->can('lifeline_cons_details_per_route'))
                            <a href="{{url('user/report/billing/lifeline_consumer_detailed_per_route')}}" class="side-menu">Lifeline Cons Details per Route</a>
                            @endif
                            @if(auth()->user()->can('lifeline_discount_per_level'))
                            <a href="{{url('user/report/billing/monthly_summary_of_lifeline_discount')}}" class="side-menu">Lifeline Discount per Level</a>
                            @endif
                            {{-- @if(auth()->user()->can('summary_of_refunded_bill_deposit'))
                            <a href="{{url('user/report/billing/summary_of_refunded_bill_deposit')}}" class="side-menu">Summary of Refunded Bill Deposit</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_sales_per_cons_type_all_types'))
                            <a href="{{url('user/report/billing/summary_of_sales_per_consumer_type')}}" class="side-menu">Summary of Sales per Cons Type(All Types)</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_sales_per_cons_type_town_big_load'))
                            <a href="{{url('user/report/billing/summary_of_sales_per_consumer_type_town_big_load')}}" class="side-menu">Summary of Sales per Cons Type/Town-Big Load</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('energy_sales_of_added_per_town'))
                            <a href="{{url('user/report/billing/energy_sales_of_additional_bills_per_town')}}" class="side-menu">Energy Sales of Added Per Town</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_unmetered_sl_per_town'))
                            <a href="{{url('user/report/billing/summary_of_unmetered_sl_per_town')}}" class="side-menu">Summary of Unmetered SL per Town</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_bills_sl_per_town_per_type'))
                            <a href="{{url('user/report/billing/summary_of_streetlights_per_town_per_type')}}" class="side-menu">Summary of Bills SL per Town per Type</a>
                            @endif
                            @if(auth()->user()->can('summary_of_metered_streetlight_all_areas'))
                            <a href="{{url('user/report/billing/summary_of_metered_streetlight')}}" class="side-menu">Summary of Metered Streetlight - All Areas</a>
                            @endif --}}
                            @if(auth()->user()->can('increase_in_consumption'))
                            <a href="{{url('user/report/billing/increase_in_consumption')}}" class="side-menu">Increase in Consumption</a>
                            @endif
                            @if(auth()->user()->can('decrease_in_consumption'))
                            <a href="{{url('user/report/billing/decrease_in_consumption')}}" class="side-menu">Decrease in Consumption</a>
                            @endif
                            {{-- @if(auth()->user()->can('consumer_count_per_kwh_used'))
                            <a href="{{url('user/report/billing/consumer_count_per_kwh_used')}}" class="side-menu">Consumer Count per KWH Used</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('scheduled_of_a_r_detail'))
                            <a href="{{url('user/report/billing/scheduled_of_ar_detailed')}}" class="side-menu">Scheduled of A/R Detail</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('scheduled_of_a_r'))
                            <a href="{{url('user/report/billing/scheduled_of_accounts_receivable')}}" class="side-menu">Scheduled of A/R</a>
                            @endif --}}
                            @if(auth()->user()->can('aging_rpt_per_route_active'))
                            <a href="{{url('user/report/billing/aging_report_active_bills_per_area')}}" class="side-menu">Aging Rpt Per Route - Active</a>
                            @endif
                            {{-- @if(auth()->user()->can('coop_consumption_per_month'))
                            <a href="{{url('user/report/billing/coop_consumption_bills')}}" class="side-menu">Coop Consumption per Month</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('summary_of_special_accnt_type_route'))
                            <a href="{{url('user/report/billing/summary_of_special_account_types')}}" class="side-menu">Summary of Special Accnt Type - Route</a>
                            @endif
                            @if(auth()->user()->can('summary_of_special_accnt_type_so'))
                            <a href="{{url('user/report/billing/summary_of_special_account_types_so')}}" class="side-menu">Summary of Special Accnt Type - SO</a>
                            @endif --}}
                            @if(auth()->user()->can('customer_sales_per_charges'))
                            <a href="{{url('user/report/billing/customer_sales_per_charges')}}" class="side-menu">Customer Sales per Charges</a>
                            @endif
                            {{-- @if(auth()->user()->can('large_loads_for_the_month'))
                            <a href="{{url('user/report/billing/large_loads_for_the_month')}}" class="side-menu">Large Loads for the Month</a>
                            @endif --}}
                            {{-- @if(auth()->user()->can('list_of_consumers_w_average_consumption'))
                            <a href="{{url('user/report/billing/list_of_consumer_with_average_consumption')}}" class="side-menu">List of Consumers w/ Average Consumption</a>
                            @endif --}}
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">COLLECTION
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('collection_for_month_per_town'))
                            <a href="{{url('user/report/collection/collection_for_month_per_town')}}" class="side-menu">Collection for Month per Town</a>
                            @endif
                            @if(auth()->user()->can('sales_versus_collection'))
                            <a href="{{url('user/report/collection/sales_versus_collection')}}" class="side-menu">Sales versus Collection</a>
                            @endif
                            @if(auth()->user()->can('reconnection_report'))
                            <a href="{{url('user/report/collection/reconnection_report')}}" class="side-menu">Reconnection Report</a>
                            @endif
                            @if(auth()->user()->can('online_payment_report'))
                            <a href="{{url('user/report/collection/online_payment')}}" class="side-menu">Online Payment Report</a>
                            @endif        
                            @if(auth()->user()->can('collection_for_month_per_consumer_type'))
                            <a href="{{url('user/report/collection/collection_for_month_per_consumer_type')}}" class="side-menu">Collection for Month per Consumer Type</a>
                            @endif
                            @if(auth()->user()->can('cashiers_collection_report_details'))
                            <a href="#">Cashiers Collection Report - Details</a>
                            @endif
                            @if(auth()->user()->can('tellers_daily_collection_report'))
                            <a href="{{url('user/report/collection/tellers_daily_collection_report')}}" class="side-menu">Tellers Daily Collection Report</a>
                            @endif
				            @if(auth()->user()->can('collection_report'))
                            <a href="{{url('user/report/collection/collection_report')}}" class="side-menu">Collection Report</a>
                            @endif
						    @if(auth()->user()->can('collection_report_per_route'))
                            <a href="{{url('user/report/collection/collection_report_per_route')}}" class="side-menu">Collection Report per Route</a>
                            @endif
                            @if(auth()->user()->can('collection_report_on_vat_pb'))
                            <a href="{{url('user/report/collection/collection_report_on_vat_pb')}}" class="side-menu">Collection Report on VAT - PB</a>
                            @endif
                            @if(auth()->user()->can('collection_report_on_vat'))
                            <a href="{{url('user/report/collection/collection_report_on_vat')}}" class="side-menu">Collection Report on VAT</a>
                            @endif
                            @if(auth()->user()->can('summary_of_void_payments'))
                            <a href="{{url('user/report/collection/summary_of_void_payments')}}" class="side-menu">Summary of Void Payments</a>
                            @endif
                            @if(auth()->user()->can('summary_of_non_bill_collection'))
                            <a href="{{url('user/report/collection/summary_of_non_bill_collection')}}" class="side-menu">Summary of Non-bill Collection</a>
                            @endif
                            @if(auth()->user()->can('collection_per_accounting_code'))
                            <a href="{{url('user/report/collection/collection_per_accounting_code')}}" class="side-menu">Collection Per Accounting Code</a>
                            @endif
                            @if(auth()->user()->can('lgu_collection_report'))
                            <a href="{{url('user/report/collection/lgu_collection_report')}}" class="side-menu">LGU Collection Report</a>
                            @endif
                            @if(auth()->user()->can('collection_summary'))
                            <a href="{{url('user/report/collection/collection_summary')}}" class="side-menu">Collection Summary</a>
                            @endif
                            @if(auth()->user()->can('collection_summary_per_date'))
                            <a href="{{url('user/report/collection/collection_summary_per_date')}}" class="side-menu">Collection Summary per Date</a>
                            @endif
                            @if(auth()->user()->can('collection_summary_per_town'))
                            <a href="{{url('user/report/collection/collection_summary_per_town')}}" class="side-menu">Collection Summary per Town</a>
                            @endif
                            @if(auth()->user()->can('dcr_control_list_per_town'))
                            <a href="{{url('user/report/collection/dcr_control_list_per_town')}}" class="side-menu">DCR Control List per Town</a>
                            @endif
                            @if(auth()->user()->can('dcr_control_list_per_route'))
                            <a href="{{url('user/report/collection/dcr_control_list_per_route')}}" class="side-menu">DCR Control List per Route</a>
                            @endif
                            @if(auth()->user()->can('summ_of_daily_collection_teller'))
                            <a href="{{url('user/report/collection/summary_of_daily_collection_teller')}}" class="side-menu">Summ of Daily Collection/Teller</a>
                            @endif
                            @if(auth()->user()->can('summ_of_daily_coll_office_acctng_code'))
                            <a href="{{url('user/report/collection/summary_of_daily_collection_office_acctng_code')}}" class="side-menu">Summ of Daily Coll./Office/Acctng Code</a>
                            @endif
                            <!-- @if(auth()->user()->can('cashiers_dcr'))
                            <a href="{{url('user/report/collection/cashiers_dcr')}}" class="side-menu">Cashier's DCR</a>
                            @endif -->
                            @if(auth()->user()->can('list_of_voided_receipts'))
                            <a href="{{url('user/report/collection/list_of_voided_receipts')}}" class="side-menu">List of Voided Receipts</a>
                            @endif
                            @if(auth()->user()->can('collected_surcharge_report'))
                            <a href="{{url('user/report/collection/collected_surcharge_report')}}" class="side-menu">Collected Surcharge Report</a>
                            @endif
                            @if(auth()->user()->can('cashiers_collection_report_nb'))
                            <a href="{{url('user/report/collection/cashiers_collection_report_nb')}}" class="side-menu">Cashiers Collection Report - NB</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">ACCOUNTING
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('summary_of_universal_charges'))
                            <a href="{{url('user/report/accounting/summary_of_universal_charges')}}" class="side-menu">Summary of Universal Charges</a>
                            @endif
                            @if(auth()->user()->can('vat_sales_collection_per_town'))
                            <a href="{{url('user/report/accounting/vat_sales_collection_per_town')}}" class="side-menu">VAT Sales Collection per Town</a>
                            @endif
                            @if(auth()->user()->can('summary_of_others_charges'))
                            <a href="{{url('user/report/accounting/summary_of_other_charges')}}" class="side-menu">Summary of Others Charges</a>
                            @endif
                            @if(auth()->user()->can('summ_of_sales_collected_ar_fit_all'))
                            <a href="{{url('user/report/accounting/summary_of_sales_collected_ar_fit_all')}}" class="side-menu">Summ of Sales/Collected AR - Fit All</a>
                            @endif
                            @if(auth()->user()->can('cashiers_dcr_collection'))
                            <a href="{{url('user/report/accounting/cashiers_dcr_collection')}}" class="side-menu">Cashiers DCR Collection</a>
                            @endif
                            @if(auth()->user()->can('monthly_cashiers_dcr_so'))
                            <a href="{{url('user/report/accounting/monthly_cashiers_dcr_so')}}" class="side-menu">Monthly Cashiers DCR - SO</a>
                            @endif
							@if(auth()->user()->can('unbundled_daily_collection_report'))
                            <a href="{{url('user/report/accounting/unbundled_collection_report')}}" class="side-menu">Unbundled Daily Collection Report</a>
                            @endif
                            @if(auth()->user()->can('collection_report_on_vat_per_date'))
                            <a href="{{url('user/report/accounting/collection_report_on_vat_per_date')}}" class="side-menu">Collection Report on VAT - per date</a>
                            @endif
                            <!-- @if(auth()->user()->can('print_prompt_payor'))
                            <a href="{{url('user/report/accounting/print_prompt_payor')}}" class="side-menu">Print Prompt Payor</a>
                            @endif -->
                            @if(auth()->user()->can('summary_of_consumer_per_bill_deposit'))
                            <a href="{{url('user/report/accounting/summary_of_consumer_per_bill_deposit')}}" class="side-menu">Summary of Consumer per Bill Deposit</a>
                            @endif
                            @if(auth()->user()->can('operating_revenue'))
                            <a href="{{url('user/report/accounting/operating_revenue')}}" class="side-menu">Operating Revenue</a>
                            @endif
                            @if(auth()->user()->can('actual_evat_collection_billed_for_the_month'))
                            <a href="{{url('user/report/accounting/actual_vat_collection')}}" class="side-menu">Actual EVAT Collection Billed for the Month</a>
                            @endif
                            @if(auth()->user()->can('vat_sales_per_consumer_type'))
                            <a href="{{url('user/report/accounting/vat_sales_per_consumer_type')}}" class="side-menu">VAT Sales per Consumer Type</a>
                            @endif
                            @if(auth()->user()->can('summary_of_mcc_rf_sc'))
                            <a href="{{url('user/report/accounting/summary_of_mcc_rf_sc')}}" class="side-menu">Summary of MCC / RF SC</a>
                            @endif
                            @if(auth()->user()->can('actual_vat_others_collection'))
                            <a href="{{url('user/report/accounting/actual_vat_others_collection/')}}" class="side-menu">Actual VAT Others Collection</a>
                            @endif
                            @if(auth()->user()->can('bill_deposit'))
                            <a href="{{url('user/report/accounting/bill_deposit_report')}}" class="side-menu">Bill Deposit</a>
                            @endif
                            @if(auth()->user()->can('print_delinquent'))
                            <a href="{{url('user/report/accounting/delinquent')}}" class="side-menu">Print Delinquent</a>
                            @endif
                            @if(auth()->user()->can('print_prompt_payor'))
                            <a href="{{url('user/report/accounting/prompt_payor')}}" class="side-menu">Print Prompt Payor</a>
                            @endif
                            <!-- @if(auth()->user()->can('print_delinquent_consumers'))
                            <a href="{{url('user/report/accounting/print_delinquent_consumers')}}" class="side-menu">Print Delinquent Consumers</a>
                            @endif -->
                            @if(auth()->user()->can('summary_of_collected_sr_citizen_per_town'))
                            <a href="{{url('user/report/accounting/summary_of_collected_sr_citizen_per_town')}}" class="side-menu">Summary of Collected Sr. Citizen per Town</a>
                            @endif
                            @if(auth()->user()->can('5%_vat_collected_report_lgus'))
                            <a href="{{url('user/report/accounting/5%_vat_collected_report_lgus')}}" class="side-menu">5% VAT Collected Report – LGUs</a>
                            @endif
                            @if(auth()->user()->can('summary_of_sales_unbundled_for_lgus'))
                            <a href="{{url('user/report/accounting/summary_of_sales_unbundled_for_lgus')}}" class="side-menu">Summary of Sales Unbundled for LGUs</a>
                            @endif
                            @if(auth()->user()->can('lgu_consumer_listing'))
                            <a href="{{url('user/report/accounting/lgu_consumer_listing')}}" class="side-menu">LGU Consumer Listing</a>
                            @endif
                            @if(auth()->user()->can('collection_report_on_vat_pb'))
                            <a href="{{url('user/report/accounting/collection_report_on_vat_pb_accounting')}}" class="side-menu">Collection Report on VAT - PB</a>
                            @endif
                            @if(auth()->user()->can('collection_on_vat'))
                            <a href="{{url('user/report/accounting/collection_report_on_vat_per_date')}}" class="side-menu">Collection on VAT</a>
                            @endif
                            @if(auth()->user()->can('5%_f_vat_collection'))
                            <a href="{{url('user/report/accounting/5%_f_vat_collection_report')}}" class="side-menu">5% F - VAT Collection</a>
                            @endif
                            @if(auth()->user()->can('sales_closing'))
                            <a href="{{url('user/report/accounting/sales_closing')}}" class="side-menu">Sales Closing</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">AUDIT
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('uncollected_bills_per_town'))
                            <a href="{{url('user/report/audit/uncollected_bills_per_town')}}" class="side-menu">Uncollected Bills per Town</a>
                            @endif
                            @if(auth()->user()->can('uncollected_bills_per_consumer'))
                            <a href="{{url('user/report/audit/uncollected_bills_per_consumer')}}" class="side-menu">Uncollected Bills per Consumer</a>
                            @endif
                            @if(auth()->user()->can('uncollected_bills_per_route'))
                            <a href="{{url('user/report/audit/uncollected_bills_per_route')}}" class="side-menu">Uncollected Bills per Route</a>
                            @endif
                            @if(auth()->user()->can('aging_report_area_town_route'))
                            <a href="{{url('user/report/audit/aging_report_atr')}}" class="side-menu">Aging Report(Area/Town/Route)</a>
                            @endif
                            @if(auth()->user()->can('inventory_of_power_bills'))
                            <a href="{{url('user/report/audit/inventory_of_power_bills')}}" class="side-menu">Inventory of Power Bills</a>
                            @endif
                            @if(auth()->user()->can('summary_of_inventory_of_active_pb_town'))
                            <a href="{{url('user/report/audit/summary_of_inventory_of_active_pb_town')}}" class="side-menu">Summary of Inventory of Active PB/Town</a>
                            @endif
                            @if(auth()->user()->can('summary_of_inventory_of_active_pb_route'))
                            <a href="{{url('user/report/audit/summary_of_inventory_of_active_pb_route')}}" class="side-menu">Summary of Inventory of Active PB/Route</a>
                            @endif
                            @if(auth()->user()->can('collection_efficiency_per_town'))
                            <a href="{{url('user/report/audit/collection_effeciency')}}" class="side-menu">Collection Efficiency per Town</a>
                            @endif
                            @if(auth()->user()->can('collection_efficiency_graph'))
                            <a href="{{url('user/report/audit/collection_efficiency_graph')}}" class="side-menu">Collection Efficiency Graph</a>
                            @endif
                            @if(auth()->user()->can('monthly_report_nb'))
                            <a href="{{url('user/report/audit/monthly_report_nb')}}" class="side-menu">Monthly Report - NB</a>
                            @endif
                            @if(auth()->user()->can('monthly_collection_by_cashier'))
                            <a href="{{url('user/report/audit/monthly_cashier_report')}}" class="side-menu">Monthly Collection by Cashier</a>
                            @endif
                            @if(auth()->user()->can('master_route_control'))
                            <a href="{{url('user/report/audit/master_route_control')}}" class="side-menu">Master Route Control</a>
                            @endif
                            @if(auth()->user()->can('route_control'))
                            <a href="{{url('user/report/audit/route_control')}}" class="side-menu">Route Control</a>
                            @endif
                            @if(auth()->user()->can('uncollected_other_operating_rev_cons'))
                            <a href="{{url('user/report/audit/uncollected_other_operating_rev_cons')}}" class="side-menu">Uncollected Other Operating Rev./Cons</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">ENGINEERING
                        <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('list_of_consumer_with_demand'))
                            <a href="{{url('user/report/engineering/list_of_consumer_with_demand')}}" class="side-menu">List of Consumer with Demand</a>
                            @endif
                            @if(auth()->user()->can('power_sales_per_substation'))
                            <a href="{{url('user/report/engineering/power_sales_per_substation')}}" class="side-menu">Power Sales per Substation</a>
                            @endif
                            @if(auth()->user()->can('town_consumption_graph'))
                            <a href="{{url('user/report/engineering/town_consumption_graph')}}" class="side-menu">Town Consumption Graph</a>
                            @endif
                            @if(auth()->user()->can('feeder_per_substation_vs_actual_consumption'))
                            <a href="{{url('user/report/engineering/feeder_per_substation_vs_actual_consumption')}}" class="side-menu">Feeder per Substation vs Actual Consumption</a>
                            @endif
                            @if(auth()->user()->can('annual_consumption_graph'))
                            <a href="{{url('user/report/engineering/annual_consumption_graph')}}" class="side-menu">Annual Consumption Graph</a>
                            @endif
                            @if(auth()->user()->can('increase_decrease_in_consumption'))
                            <a href="{{url('user/report/engineering/increase_decrease_in_consumption')}}" class="side-menu">Increase Decrease in Consumption</a>
                            @endif
                            @if(auth()->user()->can('multiplier_report'))
                            <a href="{{url('user/report/engineering/multiplier_report')}}" class="side-menu">Multiplier Report</a>
                            @endif
                            @if(auth()->user()->can('annual_consumption_per_type'))
                            <a href="{{url('user/report/engineering/annual_consumption_per_type')}}" class="side-menu">Annual Consumption Per Type</a>
                            @endif
                            @if(auth()->user()->can('customer_data_erc_dsl_02'))
                            <a href="{{url('user/report/engineering/customer_date_erc_dsl_02')}}" class="side-menu">Customer Data-(ERC-DSL-02)</a>
                            @endif
                            @if(auth()->user()->can('customer_data_erc_dsl_04'))
                            <a href="{{url('user/report/engineering/customer_date_erc_dsl_04')}}" class="side-menu">Customer Data-(ERC-DSL-04)</a>
                            @endif
                            @if(auth()->user()->can('computation_of_system_loss_per_substation'))
                            <a href="{{url('user/report/engineering/computation_of_system_loss_per_substation')}}" class="side-menu">Computation of System loss per Substation</a>
                            @endif
                            @if(auth()->user()->can('computation_of_system_loss_lasureco'))
                            <a href="{{url('user/report/engineering/system_loss')}}" class="side-menu">Computation of System loss LASURECO</a>
                            @endif
                            @if(auth()->user()->can('big_load'))
                            <a href="{{url('user/report/engineering/big_loads')}}" class="side-menu">Big Load</a>
                            @endif
                            @if(auth()->user()->can('computation_of_system_loss_per_town'))
                            <a href="{{url('user/report/engineering/system_loss_per_town')}}" class="side-menu">Computation of System loss per Town</a>
                            @endif
                            @if(auth()->user()->can('consumers_yearly_consumption'))
                            <a href="{{url('user/report/engineering/consumer_yearly_consumption')}}" class="side-menu">Consumers Yearly Consumption</a>
                            @endif
                            @if(auth()->user()->can('output_to_excel_1'))
                            <a href="{{url('user/report/engineering/output_to_excel_1')}}" class="side-menu">Output to EXCEL 1</a>
                            @endif
                            @if(auth()->user()->can('output_to_excel_2'))
                            <a href="{{url('user/report/engineering/output_to_excel_2')}}" class="side-menu">Output to EXCEL 2</a>
                            @endif
                            @if(auth()->user()->can('consumers_list_per_transformer'))
                            <a href="{{url('user/report/engineering/consumer_list_per_transformer')}}" class="side-menu">Consumers List per Transformer</a>
                            @endif
                            @if(auth()->user()->can('computation_of_system_loss_per_district_meter'))
                            <a href="{{url('user/report/engineering/system_loss_per_district')}}" class="side-menu">Computation of System loss per District Meter</a>
                            @endif
                            @if(auth()->user()->can('turn_on_report'))
                            <a href="{{url('user/report/engineering/turn_on_report')}}" class="side-menu">Turn-on Report</a>
                            @endif
                            @if(auth()->user()->can('kwh_meter_installed_per_route'))
                            <a href="{{url('user/report/engineering/kwh_meter_installed')}}" class="side-menu">KWH Meter Installed per Route</a>
                            @endif
                        </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">ISD
                            <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="dropdown-scrollable">
                            @if(auth()->user()->can('print_disconnection_notice'))
                            <a href="{{url('user/report/isd/penalty')}}" class="side-menu">Penalty</a>
                            @endif
                            @if(auth()->user()->can('consumer_listing'))
                            <a href="{{url('user/report/isd/consumer_listing')}}" class="side-menu">Consumer Listing</a>
                            @endif
                            @if(auth()->user()->can('master_list'))
                            <a href="{{url('user/report/isd/master_list')}}" class="side-menu">Master List</a>
                            @endif
                            @if(auth()->user()->can('list_of_remarked_consumers'))
                            <a href="{{url('user/report/isd/list_of_remarked_consumers')}}" class="side-menu">List of Remarked Consumers</a>
                            @endif
                            @if(auth()->user()->can('kwh_report'))
                            <a href="{{url('user/report/isd/kwh_report')}}" class="side-menu">kWh Report </a>
                            @endif
                            @if(auth()->user()->can('new_connections_per_month'))
                            <a href="{{url('user/report/isd/new_connection_per_month')}}" class="side-menu">New Connections per Month</a>
                            @endif
                            @if(auth()->user()->can('new_connections_per_consumer'))
                            <a href="{{url('user/report/isd/new_connection_per_consumer')}}" class="side-menu">New Connections per Consumer</a>
                            @endif
                            @if(auth()->user()->can('bill_deposit_report'))
                            <a href="{{url('user/report/isd/bill_deposit_report')}}" class="side-menu">Bill Deposit Report</a>
                            @endif
                            @if(auth()->user()->can('meter_deposit_report'))
                            <a href="{{url('user/report/isd/meter_deposit_report')}}" class="side-menu">Meter Deposit Report</a>
                            @endif
                            @if(auth()->user()->can('summary_of_issued_kwh_meter'))
                            <a href="{{url('user/report/isd/summary_of_issued_kwh_meter')}}" class="side-menu">Summary of Issued KWH Meter</a>
                            @endif
                            @if(auth()->user()->can('summary_of_changed_status'))
                            <a href="{{url('user/report/isd/summary_of_changed_status')}}" class="side-menu">Summary of Changed Status</a>
                            @endif
                            @if(auth()->user()->can('summary_of_transferred_meter'))
                            <a href="{{url('user/report/isd/summary_of_transferred')}}" class="side-menu">Summary of Transferred Meter</a>
                            @endif
                            @if(auth()->user()->can('summary_of_changed_consumer_type'))
                            <a href="{{url('user/report/isd/summary_of_changed_consumer_type')}}" class="side-menu">Summary of Changed Consumer Type</a>
                            @endif
                            @if(auth()->user()->can('summary_of_changed_meter'))
                            <a href="{{url('user/report/isd/summary_of_changed_meter')}}" class="side-menu">Summary of Changed Meter</a>
                            @endif
                            @if(auth()->user()->can('summary_of_metered_and_unmetered_consumers'))
                            <a href="{{url('user/report/isd/summary_of_metered_and_unmetered_consumers')}}" class="side-menu">Summary of Metered and Unmetered Consumers</a>
                            @endif
                            @if(auth()->user()->can('summary_of_changed_name'))
                            <a href="{{url('user/report/isd/summary_of_changed_name')}}" class="side-menu">Summary of Changed Name</a>
                            @endif
                            @if(auth()->user()->can('summary_of_newly_entered_consumers'))
                            <a href="{{url('user/report/isd/summary_of_newly_entered_consumers')}}" class="side-menu">Summary of Newly Entered Consumers</a>
                            @endif
                            @if(auth()->user()->can('print_disconnection_notice'))
                            <a href="{{url('user/report/isd/print_disconnection_notice')}}" class="side-menu">Print Disconnection Notice</a>
                            @endif
                            @if(auth()->user()->can('list_of_consumer_for_disconnection'))
                            <a href="{{url('user/report/isd/list_of_consumer_disconnection')}}" class="side-menu">List of Consumer for Disconnection</a>
                            @endif
                            @if(auth()->user()->can('total_collection_report'))
                            <a href="{{url('user/report/isd/total_collection_report')}}" class="side-menu">Total Collection Report</a>
                            @endif
                            @if(auth()->user()->can('total_collection_report(nonbill)'))
                            <a href="{{url('user/report/isd/total_collection_report(nonbill)')}}" class="side-menu">Total Collection Report(Non-Bill)</a>
                            @endif
                            @if(auth()->user()->can('total_collection_report(allbill)'))
                            <a href="{{url('user/report/isd/total_collection_report(all_bill)')}}" class="side-menu">Total Collection Report(All Bill)</a>
                            @endif
                            @if(auth()->user()->can('sales_vs_collection'))
                            <a href="{{url('user/report/isd/sales_vs_collection')}}" class="side-menu">Sales vs Collections Report</a>
                            @endif
                            @if(auth()->user()->can('disconnection_feed_backing'))
                            <a href="{{url('user/report/isd/disconnection_feed_backing')}}" class="side-menu">Disconnection Feed Backing</a>
                            @endif
                            @if(auth()->user()->can('disconnection_status'))
                            <a href="{{url('user/report/isd/disconnection_status')}}" class="side-menu">Disconnection Status</a>
                            @endif
                            @if(auth()->user()->can('summary_of_disco_acct_per_month'))
                            <a href="{{url('user/report/isd/summary_of_disconnected_accounts_per_month')}}" class="side-menu">Summary of Disco. Acct. per Month</a>
                            @endif
                            @if(auth()->user()->can('summary_of_disco_acct_per_period'))
                            <a href="{{url('user/report/isd/summary_of_disconnected_accounts_per_period_by_route')}}" class="side-menu">Summary of Disco. Acct. per Period by Route</a>
                            @endif
                            @if(auth()->user()->can('summary_of_disco_consumer_accomp'))
                            <a href="{{url('user/report/isd/summary_of_disconnected_consumer_with_accomplishment_report')}}" class="side-menu">Summary of Disco. Consumer - Accomp</a>
                            @endif
                            @if(auth()->user()->can('employees_pb_for_salary_deduction'))
                            <a href="{{url('user/report/isd/employees_pb_of_salary_deduction')}}" class="side-menu">Employees PB for Salary Deduction</a>
                            @endif
                            @if(auth()->user()->can('consumer_for_reconnection'))
                            <a href="{{url('user/report/isd/consumer_for_reconnection')}}" class="side-menu">Consumer for Reconnection</a>
                            @endif
                            @if(auth()->user()->can('update_consumer'))
                            <a href="#">Update Consumer</a>
                            @endif
                        </div>
                    </div>
                    <div class="dropdown-container3 submenu-maintenance submenu">
                        @if(auth()->user()->can('consumers'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer' ? 'active1' : ''}}">CONSUMER</button>
                        @endif
                        @if(auth()->user()->can('pending_consumers'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer/pending')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer/pending' ? 'active1' : ''}}">PENDING CONSUMERS</button>
                        @endif
                                <!-- new online -->
                        @if(auth()->user()->can('delete_consumer'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer/delete_consumer')}}'" class="dropdown-btn side-menu">DELETE CONSUMER</button>
                        @endif
                        @if(auth()->user()->can('online_pending'))
                        @php
                            $count = DB::table('cons_master')->where('cm_remarks',"online")->where('pending',1)->count();
                        @endphp
                        {{-- <button style = "font-size: 15px;{{ ($count > 0) ? 'color: red;' : '' }}" onclick="window.location.href='{{url('/user/maintenance/consumer/online_application_list')}}'" class="dropdown-btn side-menu"><b><span style="color: red;top: -10px;left: -10px;">{{ ($count > 0) ? $count : '' }}  </span></b>PENDING ONLINE APPLICATION</button> --}}
                        <button style = "font-size: 15px;{{ ($count > 0) ? 'color: red;' : '' }}" onclick="window.location.href='{{url('/user/maintenance/consumer/online_application_list')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer/online_application_list' ? 'active1' : ''}}"><b><span style="color: red;top: -10px;left: -10px;">{{ ($count > 0) ? $count : '' }}  </span></b>PENDING ONLINE APPLICATION</button>
                        @endif
                        {{-- @if(auth()->user()->can('online_pending'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer/online_application_list')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer/online_application_list' ? 'active1' : ''}}">PENDING ONLINE APPLICATION</button>
                        @endif --}}
                        @if(auth()->user()->can('modify_to_old'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer/modify/to/old/index')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer/modify/to/old/index' ? 'active1' : ''}}">CONSUMER ACCOUNT MODIFY</button>
                        @endif
                        @if(auth()->user()->can('approved_application'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer/approved_application')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer/approved_application' ? 'active1' : ''}}">APPROVED APPLICATIONS</button>
                        @endif
                        @if(auth()->user()->can('complaint_store'))
                        <button style="font-size: 15px;" onclick="location.href='{{url('/user/maintenance/complaintStore')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/complaintStore' ? 'active1' : ''}}">CREATE COMPLAINT</button>
                        @endif
                        @if(auth()->user()->can('complaint_show'))
                        <button style="font-size: 15px;" onclick="location.href='{{route('complaint.show')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/complaint/show' ? 'active1' : ''}}">VIEW COMPLAINT</button>
                        @endif
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer_ledger_inquiry')}}'" class="dropdown-btn1 {{ Request::path() == 'user/maintenance/consumer_ledger_inquiry' ? 'active1' : ''}}">CONSUMER LEDGER INQUIRY</button>
                        
                        <button style = "font-size: 15px;" class="dropdown-btn">CODES
                            <i class="fas fa-caret-down"></i>
                        </button>
                            <div class="dropdown-scrollable">
                                @if(auth()->user()->can('routes_codes'))
                                <a href="{{url('user/maintenance/codes/route_codes')}}" class="side-menu">Routes Codes</a>
                                @endif
                                @if(auth()->user()->can('consumer_type'))
                                <a href="{{url('user/maintenance/codes/consumer_types')}}" class="side-menu">Consumer Type</a>
                                @endif
                                @if(auth()->user()->can('accounting_codes'))
                                <a href="{{url('user/maintenance/codes/accounting_codes')}}" class="side-menu">Accounting Codes</a>
                                @endif
                                @if(auth()->user()->can('substation_codes'))
                                <a href="{{url('user/maintenance/codes/substation_codes')}}" class="side-menu">Substation Codes</a>
                                @endif
                                @if(auth()->user()->can('feeder_codes'))
                                <a href="{{url('user/maintenance/codes/feeder_codes')}}" class="side-menu">Feeder Codes</a>
                                @endif
                                @if(auth()->user()->can('transformer'))
                                <a href="{{url('user/maintenance/codes/transformer_codes')}}" class="side-menu">Transformer</a>
                                @endif
                                @if(auth()->user()->can('pole'))
                                <a href="{{url('user/maintenance/codes/pole_codes')}}" class="side-menu">Pole</a>
                                @endif
                                @if(auth()->user()->can('kwh_meter'))
                                <a href="{{url('user/maintenance/codes/kwh_meter')}}" class="side-menu">KWH Meter</a>
                                @endif
                                @if(auth()->user()->can('meter_reading_assignment'))
                                <a href="{{url('user/maintenance/codes/meter_reading_assignment')}}" class="side-menu">Meter Reading Assignment</a>
                                @endif
                                @if(auth()->user()->can('office_codes'))
                                <a href="{{url('user/maintenance/codes/office_codes')}}" class="side-menu">Office Codes</a>
                                @endif
                                @if(auth()->user()->can('barangay_group_code'))
                                <a href="{{url('user/maintenance/codes/baranggay_group_codes')}}" class="side-menu">Baranggay Group Code</a>
                                @endif
                                @if(auth()->user()->can('tsf_phase'))
                                <a href="{{url('user/maintenance/codes/tsf_phase')}}" class="side-menu">TSF Phase</a>
                                @endif
                                @if(auth()->user()->can('employee_master'))
                                <a href="{{url('user/maintenance/codes/employee_master')}}" class="side-menu">Employee Master</a>
                                @endif
                                @if(auth()->user()->can('brand_codes'))
                                <a href="{{url('user/maintenance/codes/brand_codes')}}" class="side-menu">Brand Codes</a>
                                @endif
                                @if(auth()->user()->can('meter_condition'))
                                <a href="{{url('user/maintenance/codes/meter_condition')}}" class="side-menu">Meter Condition</a>
                                @endif
                            </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">PERIODIC ENTRIES
                            <i class="fas fa-caret-down"></i>
                        </button>
                            <div class="dropdown-scrollable">
                                @if(auth()->user()->can('billing_rates'))
                                <a href="{{url('user/maintenance/periodic_entries/billing_rates')}}" class="side-menu">Billing Rates</a>
                                @endif
                                @if(auth()->user()->can('lifeline_rates'))
                                <a href="{{url('user/maintenance/periodic_entries/lifeline_rates')}}" class="side-menu">Lifeline Rates</a>
                                @endif
                                @if(auth()->user()->can('kwh_purchased'))
                                <a href="{{url('/kWH_Purchased/kWHPurchased')}}" class="side-menu">kWh Purchased</a>
                                @endif
                                @if(auth()->user()->can('holidays_weekends_sched'))
                                <a href="{{url('user/maintenance/periodic_entries/holiday_weekend_schedule')}}" class="side-menu">Holidays/Weekends Sched</a>
                                @endif
                                @if(auth()->user()->can('metering_point_purchased'))
                                <a href="{{url('user/maintenance/periodic_entries/metering_point_purchased')}}" class="side-menu">Metering Point Purchased</a>
                                @endif
                            </div>
                        <button style = "font-size: 15px;" class="dropdown-btn">
                            USERS ROLE & <br> PERMISSION
                            <i class="fas fa-caret-down"></i>
                        </button>
                            <div class="dropdown-scrollable">
                                @if(auth()->user()->can('roles'))
                                    <a href="{{url('user/maintenance/security/role')}}" class="side-menu">Roles</a>
                                @endif
                                @if(auth()->user()->can('user'))
                                <a href="{{url('user/maintenance/security/create/user')}}" class="side-menu">User</a>
                                @endif
                            </div>
                    </div>
                    <div class="dropdown-container4 submenu-inquiry submenu">
                        @if(auth()->user()->can('tellers_collection'))
                        <button style = "font-size: 15px;" href="{{url('user/inquiry/tellers_collection')}}" class="dropdown-btn">TELLERS COLLECTION</button>
                        @endif
                        @if(auth()->user()->can('consumer_inquiry'))
                        <button style = "font-size: 15px;" onclick="window.location.href='{{url('/user/maintenance/consumer_ledger_inquiry')}}'" class="dropdown-btn">CONSUMER INQUIRY</button>
                        @endif
                        @if(auth()->user()->can('meter_history'))
                        <button style = "font-size: 15px;" href="{{url('user/inquiry/meter_history')}}" class="dropdown-btn">METER HISTORY</button>
                        @endif
                        @if(auth()->user()->can('real_time_inquiry'))
                        <button style = "font-size: 15px;" href="{{url('user/inquiry/real_time_inquiry')}}" class="dropdown-btn">REAL TIME INQUIRY</button>
                        @endif
                    </div>
                    <div class="dropdown-container4 submenu-utility submenu">
                        <button style="font-size: 15px;" onclick="location.href='{{route('utility.server.ip_address')}}'" class="dropdown-btn">SERVER IP ADDRESS</button>
                        <button style="font-size: 15px;" onclick="location.href='{{route('utility.uploadcollection')}}'" class="dropdown-btn">UPLOAD COLLECTION TO SERVER</button>
                        <button style="font-size: 15px;" href="#" class="dropdown-btn">DOWNLOAD DATA FROM SERVER</button>
                        <button style="font-size: 15px;" href="#" class="dropdown-btn">METER HISTORY</button>
                        <button style="font-size: 15px;" href="#" class="dropdown-btn">REAL TIME INQUIRY</button>
                        <button style="font-size: 15px;" onclick="location.href='{{route('export.sales.data')}}'" class="dropdown-btn">EXPORT SALES DATA</button>
                        <button style="font-size: 15px;" onclick="location.href='{{route('import.sales.data')}}'" class="dropdown-btn">IMPORT SALES DATA</button>
                        <button style="font-size: 15px;" onclick="location.href='{{url('user/utility/upload')}}'" class="dropdown-btn">UPLOADED TO SERVER</button>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>