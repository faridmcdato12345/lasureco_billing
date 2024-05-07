<?php
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadCollectionToServerController;
use App\Http\Controllers\Api\AreaCodeController;
use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\ExportSalesDataController;
use App\Http\Controllers\ImportSalesDataController;
use App\Http\Controllers\ServerIpAddressController;
use App\Http\Controllers\ServerConnectionController;
use App\Http\Controllers\ConsumerPendingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DeleteConsumerController;
use App\Http\Controllers\api\BillingReportController;
use App\Models\Consumer;
use App\Models\Sales;
use App\Models\Server;
use App\Models\EWALLET_LOG;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']],function(){
	Route::get('/check_gcash',function(){
		$sales = DB::table('meter_reg')->where('cons_account','9999999992')->get();
		dd($sales);
	});
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::prefix('user')->group(function () {
    Route::prefix('/utility')->group(function(){
        Route::get('/upload_collection_to_server',[UploadCollectionToServerController::class,'index'])->name('utility.uploadcollection');
        Route::get('/ip_address',[ServerIpAddressController::class,'index'])->name('utility.server.ip_address');
    });
        Route::prefix('report')->group(function () {
            Route::prefix('meter_reading')->group(function () {
                Route::get('/reading_schedule_by_billing_period', function () {
                    return view('user.report.meter_reading.reading_schedule_by_billing_period');
                });
                Route::get('/reading_schedule_by_reading_date', function () {
                    return view('user.report.meter_reading.reading_schedule_by_reading_date');
                });
                Route::get('/reading_schedule_by_reading_date', function () {
                    return view('user.report.meter_reading.reading_schedule_by_reading_date');
                });
                Route::get('/disconnected_with_bill_by_billing_period', function () {
                    return view('user.report.meter_reading.disconnected_with_bill_by_billing_period');
                });
                Route::get('/disconnected_with_bill_per_route', function () {
                    return view('user.report.meter_reading.disconnected_with_bill_per_route');
                });
                Route::get('/cons_applied_with_average_consumption_by_bp', function () {
                    return view('user.report.meter_reading.cons_applied_with_average_consumption_by_bp');
                });
                Route::get('/cons_applied_with_ave_consumption_by_mrd', function () {
                    return view('user.report.meter_reading.cons_applied_with_ave_consumption_by_mrd');
                });
                Route::get('/unread_unbilled_cons_per_route', function () {
                    return view('user.report.meter_reading.unread_unbilled_cons_per_route');
                });
                Route::get('/unread_unbilled_cons_per_town', function () {
                    return view('user.report.meter_reading.unread_unbilled_cons_per_town');
                });
                Route::get('/cons_with_ff__lasureco_month_by_ff', function () {
                    return view('user.report.meter_reading.cons_with_ff__lasureco_month_by_ff');
                });
                Route::get('/cons_with_ff_town_month', function () {
                    return view('user.report.meter_reading.cons_with_ff_town_month');
                });
                Route::get('/cons_with_zero_kwh_town_month', function () {
                    return view('user.report.meter_reading.cons_with_zero_kwh_town_month');
                });
                Route::get('/time_in_motion_report', function () {
                    return view('user.report.meter_reading.time_in_motion_report');
                });
                Route::get('/summary_of_field_findings', function () {
                    return view('user.report.meter_reading.summary_of_field_findings');
                });
                Route::get('/mrr_1', function () {
                    return view('user.report.meter_reading.mrr_1');
                });
                Route::get('/mrr_2', function () {
                    return view('user.report.meter_reading.mrr_2');
                });
                Route::get('/mrr_inquiry_per_consumer_1', function () {
                    return view('user.report.meter_reading.mrr_inquiry_per_consumer_1');
                });
                Route::get('/mrr_inquiry_per_consumer_2', function () {
                    return view('user.report.meter_reading.mrr_inquiry_per_consumer_2');
                });
                Route::get('/meter_reading_activity_report', function () {
                    return view('user.report.meter_reading.meter_reading_activity_report');
                });
            });
            Route::prefix('billing')->group(function () {
                Route::get('/general_detail_report', function () {
                    return view('user.report.billing.general_detail_report');
                });
                Route::get('/list_of_member_consumer_per_kwh_used', function () {
                    return view('user.report.billing.list_of_member_consumer_per_kwh_used');
                });
                Route::get('/list_of_member_consumers_billed_on_average_override', function () {
                    return view('user.report.billing.list_of_member_consumers_billed_on_average_override');
                });
                Route::get('/ledger_report', function() {
                    return view('user.report.billing.ledger_report');
                });
                Route::get('/summary_of_sales_unbundled', function () {
                    return view('user.report.billing.summary_of_sales_unbundled');
                });
                Route::get('/general_summary_report', function () {
                    return view('user.report.billing.general_summary_report');
                });
                Route::get('/power_sales_per_route', function() {
                    return view('user.report.billing.power_sales_per_route');
                });
                Route::get('/summary_of_bill_adjustments_detailed', function() {
                    return view('user.report.billing.summary_of_bill_adjustments_detailed');
                });
                Route::get('/summary_of_bill_adjustments', function() {
                    return view('user.report.billing.summary_of_bill_adjustments');
                });
                Route::get('/monthly_summary_of_adjustment_dtl', function() {
                    return view('user.report.billing.monthly_summary_of_adjustment_dtl');
                });
                Route::get('/summary_of_bills', function() {
                    return view('user.report.billing.summary_of_bills');
                });
                Route::get('/summary_of_bills_additional', function() {
                    return view('user.report.billing.summary_of_bills_additional');
                });
                Route::get('/summary_of_bills_bapa', function() {
                    return view('user.report.billing.summary_of_bills_bapa');
                });
                Route::get('/summary_of_bills_unbundled', function() {
                    return view('user.report.billing.summary_of_bills_unbundled');
                });
                Route::get('/summary_of_additional_bills', function() {
                    return view('user.report.billing.summary_of_additional_bills');
                });
                Route::get('/Frequency_Distribution_of_KWH_Consumption', function() {
                    return view('user.report.billing.Frequency_Distribution_of_KWH_Consumption');
                });
                Route::get('/frequency_distribution_of_kwh_consumption_consolidated', function() {
                    return view('user.report.billing.frequency_distribution_of_kwh_consumption_consolidated');
                });
                Route::get('/list_of_consumer_with_subsidy', function() {
                    return view('user.report.billing.list_of_consumer_with_subsidy');
                });
                Route::get('/summary_of_subsidy_applied', function() {
                    return view('user.report.billing.summary_of_subsidy_applied');
                });
                Route::get('/advance_power_bill_payment_applied_to_bill', function() {
                    return view('user.report.billing.advance_power_bill_payment_applied_to_bill');
                });
                Route::get('/print_consumption_by_bracket_per_route', function() {
                    return view('user.report.billing.print_consumption_by_bracket_per_route');
                });
                Route::get('/print_consumption_by_bracket_per_town', function() {
                    return view('user.report.billing.print_consumption_by_bracket_per_town');
                });
                Route::get('/lifeline_consumer_detailed', function() {
                    return view('user.report.billing.lifeline_consumer_detailed');
                });
                Route::get('/summary_of_unread_unbilled_consumer', function() {
                    return view('user.report.billing.summary_of_unread_unbilled_consumer');
                });
                Route::get('/summary_of_consumer_per_kwh_used', function() {
                    return view('user.report.billing.summary_of_consumer_per_kwh_used');
                });
                Route::get('/lifeline_discount_subsidy', function() {
                    return view('user.report.billing.lifeline_discount_subsidy');
                });
                Route::get('/sales_per_type_with_consumer_name', function() {
                    return view('user.report.billing.sales_per_type_with_consumer_name');
                });
                Route::get('/sales_rental_with_consumer_name', function() {
                    return view('user.report.billing.sales_rental_with_consumer_name');
                });
                Route::get('/consumer_data', function() {
                    return view('user.report.billing.consumer_data');
                });
                Route::get('/list_of_consumer_not_posted_to_ledger', function() {
                    return view('user.report.billing.list_of_consumer_not_posted_to_ledger');
                });
                Route::get('/summary_of_refunded_bill_deposits', function() {
                    return view('user.report.billing.summary_of_refunded_bill_deposits');
                });
                Route::get('/summary_of_bills_amounted_issued', function() {
                    return view('user.report.billing.summary_of_bills_amounted_issued');
                });
                Route::get('/summary_of_bapa_discount_fare', function() {
                    return view('user.report.billing.summary_of_bapa_discount_fare');
                });
                Route::get('/large_load_per_route', function() {
                    return view('user.report.billing.large_load_per_route');
                });
                Route::get('/accounting_of_power_bills', function() {
                    return view('user.report.billing.accounting_of_power_bills');
                });
                Route::get('/summary_of_sales_coverage', function() {
                    return view('user.report.billing.summary_of_sales_coverage');
                });
                Route::get('/summary_of_energy_sales_per_consumer_type', function() {
                    return view('user.report.billing.summary_of_energy_sales_per_consumer_type');
                });
                Route::get('/senior_citizens_with_discount_detailed', function() {
                    return view('user.report.billing.senior_citizens_with_discount_detailed');
                });
                Route::get('/senior_citizens_discount', function() {
                    return view('user.report.billing.senior_citizens_discount');
                });
                Route::get('/additional_bills_previous_month', function() {
                    return view('user.report.billing.additional_bills_previous_month');
                });
                Route::get('/summary_of_sales_big_load', function() {
                    return view('user.report.billing.summary_of_sales_big_load');
                });
                Route::get('/summary_of_revenue_consumer_type', function() {
                    return view('user.report.billing.summary_of_revenue_consumer_type');
                });
                Route::get('/summary_of_revenue_town', function() {
                    return view('user.report.billing.summary_of_revenue_town');
                });
                Route::get('/senior_citizens_discount_per_level', function() {
                    return view('user.report.billing.senior_citizens_discount_per_level');
                });
                Route::get('/senior_citizens_age_contract', function() {
                    return view('user.report.billing.senior_citizens_age_contract');
                });
                Route::get('/lifeline_discount_per_area', function() {
                    return view('user.report.billing.lifeline_discount_per_area');
                });
                Route::get('/lifeline_discount_per_town', function() {
                    return view('user.report.billing.lifeline_discount_per_town');
                });
                Route::get('/lifeline_discount_per_route', function() {
                    return view('user.report.billing.lifeline_discount_per_route');
                });
                Route::get('/monthly_summary_lifeline_consumer', function() {
                    return view('user.report.billing.monthly_summary_lifeline_consumer');
                });
                Route::get('/lifeline_consumer_detailed_per_town', function() {
                    return view('user.report.billing.lifeline_consumer_detailed_per_town');
                });
                Route::get('/lifeline_consumer_detailed_per_route', function() {
                    return view('user.report.billing.lifeline_consumer_detailed_per_route');
                });
                Route::get('/monthly_summary_of_lifeline_discount', function() {
                    return view('user.report.billing.monthly_summary_of_lifeline_discount');
                });
                Route::get('/summary_of_refunded_bill_deposit', function() {
                    return view('user.report.billing.summary_of_refunded_bill_deposit');
                });
                Route::get('/summary_of_sales_per_consumer_type', function() {
                    return view('user.report.billing.summary_of_sales_per_consumer_type');
                });
                Route::get('/summary_of_sales_per_consumer_type_town_big_load', function() {
                    return view('user.report.billing.summary_of_sales_per_consumer_type_town_big_load');
                });
                Route::get('/energy_sales_of_additional_bills_per_town', function() {
                    return view('user.report.billing.energy_sales_of_additional_bills_per_town');
                });
                Route::get('/summary_of_unmetered_sl_per_town', function() {
                    return view('user.report.billing.summary_of_unmetered_sl_per_town');
                });
                Route::get('/summary_of_streetlights_per_town_per_type', function() {
                    return view('user.report.billing.summary_of_streetlights_per_town_per_type');
                });
                Route::get('/summary_of_metered_streetlight', function() {
                    return view('user.report.billing.summary_of_metered_streetlight');
                });
                Route::get('/increase_in_consumption', function() {
                    return view('user.report.billing.increase_in_consumption');
                });
                Route::get('/decrease_in_consumption', function() {
                    return view('user.report.billing.decrease_in_consumption');
                });
                Route::get('/consumer_count_per_kwh_used', function() {
                    return view('user.report.billing.consumer_count_per_kwh_used');
                });
                Route::get('/scheduled_of_ar_detailed', function() {
                    return view('user.report.billing.scheduled_of_ar_detailed');
                });
                Route::get('/scheduled_of_accounts_receivable', function() {
                    return view('user.report.billing.scheduled_of_accounts_receivable');
                });
                Route::get('/aging_report_active_bills_per_area', function() {
                    return view('user.report.billing.aging_report_active_bills_per_area');
                });
                Route::get('/coop_consumption_bills', function() {
                    return view('user.report.billing.coop_consumption_bills');
                });
                Route::get('/summary_of_special_account_types', function() {
                    return view('user.report.billing.summary_of_special_account_types');
                });
                Route::get('/summary_of_special_account_types_so', function() {
                    return view('user.report.billing.summary_of_special_account_types_so');
                });
                Route::get('/customer_sales_per_charges', function() {
                    return view('user.report.billing.customer_sales_per_charges');
                });
                Route::get('/large_loads_for_the_month', function() {
                    return view('user.report.billing.large_loads_for_the_month');
                });
                Route::get('/list_of_consumer_with_average_consumption', function() {
                    return view('user.report.billing.list_of_consumer_with_average_consumption');
                });
            });
            Route::prefix('collection')->group(function () {
                Route::get('/reconnection_report', function () {
                    return view('user.report.collection.reconnection_report');
                });
                Route::get('/sales_versus_collection', function () {
                    return view('user.report.collection.sales_versus_collection');
                });
                Route::get('/summary_of_non_bill_collection', function () {
                    return view('user.report.collection.summary_of_non_bill_collection');
                });
			    Route::get('/collection_report', function () {
                    return view('user.report.collection.collection_report');
                });
                Route::get('/collection_report_per_route', function () {
                    return view('user.report.collection.collection_report_per_route');
                });
                Route::get('/collection_for_month_per_town', function () {
                    return view('user.report.collection.collection_for_month_per_town');
                });
                Route::get('/online_payment', function () {
                    return view('user.report.collection.online_payment');
                });
                Route::get('/collection_for_month_per_consumer_type', function () {
                    return view('user.report.collection.Collection_for_Month_per_Consumer_Type');
                });
                Route::get('/tellers_daily_collection_report', function () {
                    return view('user.report.collection.tellers_daily_collection_report');
                });
                Route::get('/collection_report_on_vat_pb', function () {
                    return view('user.report.collection.collection_report_on_vat_pb');
                });
                Route::get('/collection_report_on_vat', function () {
                    return view('user.report.collection.collection_report_on_vat');
                });
                Route::get('/summary_of_void_payments', function () {
                    return view('user.report.collection.summary_of_void_payments');
                });
                Route::get('/collection_per_accounting_code', function () {
                    return view('user.report.collection.collection_per_accounting_code');
                });
                Route::get('/lgu_collection_report', function () {
                    return view('user.report.collection.lgu_collection_report');
                });
                Route::get('/collection_summary_per_date', function () {
                    return view('user.report.collection.collection_summary_per_date');
                });
                Route::get('/collection_summary_per_town', function () {
                    return view('user.report.collection.collection_summary_per_town');
                });
                Route::get('/collection_summary', function () {
                    return view('user.report.collection.collection_summary');
                });
                Route::get('/dcr_control_list_per_town', function () {
                    return view('user.report.collection.dcr_control_list_per_town');
                });
                Route::get('/dcr_control_list_per_route', function () {
                    return view('user.report.collection.dcr_control_list_per_route');
                });
                Route::get('/summary_of_daily_collection_teller', function () {
                    return view('user.report.collection.summary_of_daily_collection_teller');
                });
                Route::get('/summary_of_daily_collection_office_acctng_code', function () {
                    return view('user.report.collection.summary_of_daily_collection_office_acctng_code');
                });
                Route::get('/cashiers_dcr', function () {
                    return view('user.report.collection.cashiers_dcr');
                });
                Route::get('/list_of_voided_receipts', function () {
                    return view('user.report.collection.list_of_voided_receipts');
                });
                Route::get('/collected_surcharge_report', function () {
                    return view('user.report.collection.collected_surcharge_report');
                });
                Route::get('/cashiers_collection_report_nb', function () {
                    return view('user.report.collection.cashiers_collection_report_nb');
                });
            });
            Route::prefix('accounting')->group(function () {
                Route::get('/summary_of_universal_charges', function () {
                    return view('user.report.accounting.summary_of_universal_charges');
                });
                Route::get('/collection_report_on_vat_per_date', function () {
                    return view('user.report.accounting.collection_report_on_vat_per_date');
                });
                Route::get('/vat_sales_collection_per_town', function () {
                    return view('user.report.accounting.vat_sales_collection_per_town');
                });
				Route::get('/operating_revenue', function () {
                    return view('user.report.accounting.operating_revenue');
                });
                Route::get('/summary_of_other_charges', function () {
                    return view('user.report.collection.summary_of_other_charges');
                });
                Route::get('/print_prompt_payor', function () {
                    return view('user.report.collection.print_prompt_payor');
                });
                Route::get('/summary_of_consumer_per_bill_deposit', function () {
                    return view('user.report.collection.summary_of_consumer_per_bill_deposit');
                });
                Route::get('/actual_vat_collection', function () {
                    return view('user.report.accounting.actual_vat_collection');
                });
                Route::get('/vat_sales_per_consumer_type', function () {
                    return view('user.report.collection.vat_sales_per_consumer_type');
                });
                Route::get('/vat_sales_per_town', function () {
                    return view('user.report.accounting\.vat_sales_per_town');
                });
                Route::get('/summary_of_sales_unbundled_for_lgus', function () {
                    return view('user.report.accounting.summary_of_sales_unbundled_for_lgus');
                });
                Route::get('/cashiers_dcr_collection', function () {
                    return view('user.report.accounting.cashiers_dcr_collection');
                });
                Route::get('/monthly_cashiers_dcr_so', function () {
                    return view('user.report.accounting.monthly_cashiers_dcr_so');
                });
                Route::get('/unbundled_collection_report', function () {
                    return view('user.report.accounting.unbundled_collection_report');
                });
                Route::get('/bill_deposit_report', function () {
                    return view('user.report.accounting.bill_deposit_report');
                });
                Route::get('/lgu_consumer_listing', function () {
                    return view('user.report.accounting.lgu_consumer_listing');
                });
                Route::get('/summary_of_mcc_rf_sc', function () {
                    return view('user.report.collection.summary_of_mcc_rf_sc');
                });
                Route::get('/actual_vat_others_collection', function () {
                    return view('user.report.collection.actual_vat_others_collection');
                });
                Route::get('/summary_of_sales_collected_ar_fit_all', function () {
                    return view('user.report.accounting.summary_of_sales_collected_ar_fit_all');
                });
                Route::get('/print_delinquent_consumers', function () {
                    return view('user.report.collection.print_delinquent_consumers');
                });
                Route::get('/summary_of_collected_sr_citizen_per_town', function () {
                    return view('user.report.collection.summary_of_collected_sr_citizen_per_town');
                });
                Route::get('/5%_vat_collected_report_lgus', function () {
                    return view('user.report.collection.5%_vat_collected_report_lgus');
                });Route::get('/collection_report_on_vat_pb_accounting', function () {
                    return view('user.report.collection.collection_report_on_vat_pb_accounting');
                });
                Route::get('/5%_f_vat_collection_report', function () {
                    return view('user.report.collection.5%_f_vat_collection_report');
                });
                Route::get('/sales_closing', function () {
                    return view('user.report.accounting.sales_closing');
                });
                Route::get('/delinquent', function () {
                    return view('user.report.accounting.delinquent');
                });
                Route::get('/prompt_payor', function () {
                    return view('user.report.accounting.prompt_payor');
                });
            });
            Route::prefix('audit')->group(function () {
                Route::get('/uncollected_bills_per_town', function () {
                    return view('user.report.audit.uncollected_bills_per_town');
                });
                Route::get('/uncollected_bills_per_consumer', function () {
                    return view('user.report.audit.uncollected_bills_per_consumer');
                });
                Route::get('/uncollected_bills_per_route', function () {
                    return view('user.report.audit.uncollected_bills_per_route');
                });
                Route::get('/aging_report_atr', function () {
                    return view('user.report.audit.aging_report_atr');
                });
                Route::get('/inventory_of_power_bills', function () {
                    return view('user.report.audit.inventory_of_power_bills');
                });
                Route::get('/summary_of_inventory_of_active_pb_town', function () {
                    return view('user.report.audit.summary_of_inventory_of_active_pb_town');
                });
                Route::get('/summary_of_inventory_of_active_pb_route', function () {
                    return view('user.report.audit.summary_of_inventory_of_active_pb_route');
                });
                Route::get('/collection_effeciency', function () {
                    return view('user.report.audit.collection_effeciency');
                });
                Route::get('/collection_efficiency_graph', function () {
                    return view('user.report.audit.collection_efficiency_graph');
                });
                Route::get('/monthly_report_nb', function () {
                    return view('user.report.audit.monthly_report_nb');
                });
                Route::get('/monthly_cashier_report', function () {
                    return view('user.report.audit.monthly_cashier_report');
                });
                Route::get('/master_route_control', function () {
                    return view('user.report.audit.master_route_control');
                });
                Route::get('/route_control', function () {
                    return view('user.report.audit.route_control');
                });
                Route::get('/uncollected_other_operating_rev_cons', function () {
                    return view('user.report.audit.uncollected_other_operating_rev_cons');
                });
            });
            Route::prefix('engineering')->group(function () {
                Route::get('/list_of_consumer_with_demand', function () {
                    return view('user.report.engineering.list_of_consumer_with_demand');
                });
                Route::get('/power_sales_per_substation', function () {
                    return view('user.report.engineering.power_sales_per_substation');
                });
                Route::get('/town_consumption_graph', function () {
                    return view('user.report.engineering.town_consumption_graph');
                });
                Route::get('/feeder_per_substation_vs_actual_consumption', function () {
                    return view('user.report.engineering.feeder_per_substation_vs_actual_consumption');
                });
                Route::get('/annual_consumption_graph', function () {
                    return view('user.report.engineering.annual_consumption_graph');
                });
                Route::get('/increase_decrease_in_consumption', function () {
                    return view('user.report.engineering.increase_decrease_in_consumption');
                });
                Route::get('/multiplier_report', function () {
                    return view('user.report.engineering.multiplier_report');
                });
                Route::get('/annual_consumption_per_type', function () {
                    return view('user.report.engineering.annual_consumption_per_type');
                });
                Route::get('/customer_date_erc_dsl_02', function () {
                    return view('user.report.engineering.customer_date_erc_dsl_02');
                });
                Route::get('/customer_date_erc_dsl_04', function () {
                    return view('user.report.engineering.customer_date_erc_dsl_04');
                });
                Route::get('/computation_of_system_loss_per_substation', function () {
                    return view('user.report.engineering.computation_of_system_loss_per_substation');
                });
                Route::get('/system_loss', function () {
                    return view('user.report.engineering.system_loss');
                });
                Route::get('/big_loads', function () {
                    return view('user.report.engineering.big_loads');
                });
                Route::get('/system_loss_per_town', function () {
                    return view('user.report.engineering.system_loss_per_town');
                });
                Route::get('/consumer_yearly_consumption', function () {
                    return view('user.report.engineering.consumer_yearly_consumption');
                });
                Route::get('/output_to_excel_1', function () {
                    return view('user.report.engineering.output_to_excel_1');
                });
                Route::get('/output_to_excel_2', function () {
                    return view('user.report.engineering.output_to_excel_2');
                });
                Route::get('/consumer_list_per_transformer', function () {
                    return view('user.report.engineering.consumer_list_per_transformer');
                });
                Route::get('/system_loss_per_district', function () {
                    return view('user.report.engineering.system_loss_per_district');
                });
                Route::get('/turn_on_report', function () {
                    return view('user.report.engineering.turn_on_report');
                });
                Route::get('/kwh_meter_installed', function () {
                    return view('user.report.engineering.kwh_meter_installed');
                });
            });
            Route::prefix('isd')->group(function () {
                Route::get('/list_of_remarked_consumers', function () {
                    return view('user.report.isd.list_of_remarked_consumers');
                });
                Route::get('/kwh_report', function() {
                    return view('user.report.isd.kwh_report');
                });
                Route::get('/total_collection_report(all_bill)', function () {
                    return view('user.report.isd.total_collection_report(all_bill)');
                });
                Route::get('/total_collection_report(nonbill)', function () {
                    return view('user.report.isd.total_collection_report(nonbill)');
                });
                Route::get('/total_collection_report', function () {
                    return view('user.report.isd.total_collection_report');
                });
                Route::get('/sales_vs_collection', function () {
                    return view('user.report.isd.sales_vs_collection');
                });
                Route::get('/consumer_listing', function () {
                    return view('user.report.isd.consumer_listing');
                });
                Route::get('/master_list', function () {
                    return view('user.report.isd.master_list');
                });
                Route::get('/new_connection_per_month', function () {
                    return view('user.report.isd.new_connection_per_month');
                });
                Route::get('/new_connection_per_consumer', function () {
                    return view('user.report.isd.new_connection_per_consumer');
                });
                Route::get('/meter_deposit_report', function () {
                    return view('user.report.isd.meter_deposit_report');
                });
                Route::get('/summary_of_issued_kwh_meter', function () {
                    return view('user.report.isd.summary_of_issued_kwh_meter');
                });
                Route::get('/summary_of_changed_status', function () {
                    return view('user.report.isd.summary_of_changed_status');
                });
                Route::get('/summary_of_transferred', function () {
                    return view('user.report.isd.summary_of_transferred');
                });
                Route::get('/summary_of_changed_consumer_type', function () {
                    return view('user.report.isd.summary_of_changed_consumer_type');
                });
                Route::get('/summary_of_changed_meter', function () {
                    return view('user.report.isd.summary_of_changed_meter');
                });
                Route::get('/summary_of_metered_and_unmetered_consumers', function () {
                    return view('user.report.isd.summary_of_metered_and_unmetered_consumers');
                });
                Route::get('/summary_of_changed_name', function () {
                    return view('user.report.isd.summary_of_changed_name');
                });
                Route::get('/summary_of_newly_entered_consumers', function () {
                    return view('user.report.isd.summary_of_newly_entered_consumers');
                });
                Route::get('/print_disconnection_notice', function () {
                    return view('user.report.isd.print_disconnection_notice');
                });
                Route::get('/list_of_consumer_disconnection', function () {
                    return view('user.report.isd.list_of_consumer_disconnection');
                });
                Route::get('/disconnection_feed_backing', function () {
                    return view('user.report.isd.disconnection_feed_backing');
                });
                Route::get('/disconnection_status', function () {
                    return view('user.report.isd.disconnection_status');
                });
                Route::get('/summary_of_disconnected_accounts_per_month', function () {
                    return view('user.report.isd.summary_of_disconnected_accounts_per_month');
                });
                Route::get('/summary_of_disconnected_accounts_per_period_by_route', function () {
                    return view('user.report.isd.summary_of_disconnected_accounts_per_period_by_route');
                });
                Route::get('/summary_of_disconnected_consumer_with_accomplishment_report', function () {
                    return view('user.report.isd.summary_of_disconnected_consumer_with_accomplishment_report');
                });
                Route::get('/employees_pb_of_salary_deduction', function () {
                    return view('user.report.isd.employees_pb_of_salary_deduction');
                });
                Route::get('/consumer_for_reconnection', function () {
                    return view('user.report.isd.consumer_for_reconnection');
                });
                Route::get('/penalty', function () {
                    return view('user.report.isd.penalty');
                });
            });
        });
        Route::prefix('maintenance')->group(function () {
            Route::prefix('/security')->group(function(){
                Route::get('/role',function(){
                    return view('user.maintenance.security.role');
                });
                Route::get('/role/add',function(){
                    return view('user.maintenance.security.addRole');
                });
                Route::get('/permission',function(){
                    return view('user.maintenance.security.permission');
                });
                Route::get('/create/user',function(){
                    $roles = Role::all();
                    return view('user.maintenance.security.userIndex',compact('roles'));
                });
                
            });
            Route::prefix('consumer')->group(function () {
                Route::get('/',function(){
                    return view('user.maintenance.consumer.index');
                });
                Route::get('/pending',function(){
                    return view('user.maintenance.consumer.pending');
                });
                Route::get('/delete_consumer',function(){
                    return view('user.maintenance.consumer.delete_consumer');
                });
                Route::get('/online_application_list',[ConsumerPendingController::class,'onlineAppList'])->name('online.app.list');
                Route::get('/approved_application',[ConsumerPendingController::class,'approvedAppList'])->name('approved.app.list');
                Route::get('/online_application_list/{id}',[ConsumerPendingController::class,'show'])->name('online.app.show');
                Route::get('/get_last_consumer/{id}',[ConsumerPendingController::class,'getLastCons'])->name('get.last.consumer');
                Route::get('/approve_consumer/{id}',[ConsumerPendingController::class,'approveConsumer'])->name('approve.consumer');
                Route::delete('/online_application/reject/{id}',[ConsumerPendingController::class,'rejectOnline'])->name('reject.online');
                Route::post('/set_consumer_account',[ConsumerPendingController::class,'setConsAccount'])->name('set.cons.account');
                Route::get('/online_application/inspection/{id}',[ConsumerPendingController::class,'generateInspectionReport'])->name('gen.inspection');
                Route::get('/online_application/verification/{id}',[ConsumerPendingController::class,'generateVerificationReport'])->name('gen.verification');
                Route::post('/set_inspector_report',[ConsumerPendingController::class,'updateInspector'])->name('set.inspector.report');
                Route::post('/set_or',[ConsumerPendingController::class,'updateOR'])->name('set.or');
                Route::POST('/modify/to/old',[ConsumerController::class,'modifyToOld'])->name('modify.to.old');
                Route::get('/modify/to/old/index',function(){
                    return view('user.maintenance.consumer.consumer_account_to_old');
                });
            });
            Route::prefix('consumer_ledger_inquiry')->group(function () {
                Route::get('/',function(){
                    return view('user.maintenance.consumer_ledger_inquiry');
                });
            });
            Route::prefix('complaintStore')->group(function () {
                Route::get('/',function(){
                    return view('user.maintenance.complaintStore');
                });
            });
            Route::prefix('codes')->group(function () {
                Route::get('/area_codes', function() {
                    return view('user.maintenance.codes.area_codes');
                });
                Route::get('/town_codes', function() {
                    return view('user.maintenance.codes.town_codes');
                });
                Route::get('/route_codes', function() {
                    return view('user.maintenance.codes.route_codes');
                });
                Route::get('/consumer_types', function() {
                    return view('user.maintenance.codes.consumer_types');
                });
                Route::get('/accounting_codes', function() {
                    return view('user.maintenance.codes.accounting_codes');
                });
                Route::get('/substation_codes', function() {
                    return view('user.maintenance.codes.substation_codes');
                });
                Route::get('/feeder_codes', function() {
                    return view('user.maintenance.codes.feeder_codes');
                });
                Route::get('/transformer_codes', function() {
                    return view('user.maintenance.codes.transformer_codes');
                });
                Route::get('/pole_codes', function() {
                    return view('user.maintenance.codes.pole_codes');
                });
                Route::get('/kwh_meter', function() {
                    return view('user.maintenance.codes.kwh_meter');
                });
                Route::get('/de_meter_reading_gadget', function() {
                    return view('user.maintenance.codes.de_meter_reading_gadget');
                });
                Route::get('/meter_reading_assignment', function() {
                    return view('user.maintenance.codes.meter_reading_assignment');
                });
                Route::get('/office_codes', function() {
                    return view('user.maintenance.codes.office_codes');
                });
                Route::get('/baranggay_group_codes', function() {
                    return view('user.maintenance.codes.baranggay_group_codes');
                });
                Route::get('/tsf_phase', function() {
                    return view('user.maintenance.codes.tsf_phase');
                });
                Route::get('/employee_master', function() {
                    return view('user.maintenance.codes.employee_master');
                });
                Route::get('/brand_codes', function() {
                    return view('user.maintenance.codes.brand_codes');
                });
                Route::get('/meter_condition', function() {
                    return view('user.maintenance.codes.meter_condition');
                });
                Route::get('/meter_condition', function() {
                    return view('user.maintenance.codes.meter_condition');
                });
            });
            Route::prefix('periodic_entries')->group(function () {
                Route::get('/billing_rates', function() {
                    return view('user.maintenance.periodic_entries.billing_rates');
                });
                Route::get('/lifeline_rates', function() {
                    return view('user.maintenance.periodic_entries.lifeline_rates');
                });
                Route::get('/holiday_weekend_schedule', function() {
                    return view('user.maintenance.periodic_entries.holiday_weekend_schedule');
                });
                Route::get('/metering_point_purchased', function() {
                    return view('user.maintenance.periodic_entries.metering_point_purchased');
                });
            });
            Route::prefix('utility')->group(function(){
                Route::get('/upload_collection_to_server',[UploadCollectionToServerController::class,'index'])->name('upload.collection');
            });
        });
        Route::prefix('transaction')->group(function () {
            // Route::get('/',function(){
            //     return view('user.transaction.index');
            // });
            Route::prefix('meter_reading')->group(function () {
                Route::get('/mrs_print', function () {
                    return view('print.mrs_pdf_layout');
                })->name('MRSprint');
                Route::get('/mrs1_print', function () {
                    return view('print.mrs1_pdf_layout');
                })->name('MRSprint1');
                Route::get('/print_meter_reading_sheet', function () {
                    return view('user.transaction.meter_reading.print_meter_reading_sheet');
                });
                Route::group(['middleware' => ['permission:list_of_receiving_of_sao']], function () {
                    Route::get('/list_of_receiving_of_sao', function () {
                        return view('user.transaction.meter_reading.list_of_receiving_of_sao');
                    });
                });
                Route::get('/sao_acknowledgement_receipt', function () {
                    return view('user.transaction.meter_reading.sao_acknowledgement_receipt');
                });
                Route::get('/renumber_consumer_sequence', function () {
                    return view('user.transaction.meter_reading.renumber_consumer_sequence');
                });
            });
            Route::prefix('/billing')->group(function() {
                Route::get('/enter_meter_readings', function () {
                    return view('user.transaction.billing.enter_meter_readings');
                });
                Route::get('/print_billing_edit_list', function () {
                    return view('user.transaction.billing.print_billing_edit_list');
                });
                Route::get('/power_bill_adjustments', function () {
                    return view('user.transaction.billing.power_bill_adjustments');
                });
                Route::get('/print_new_bill', function () {
                    return view('user.transaction.billing.print_new_bill');
                });
                Route::get('/re_print_bill', function () {
                    return view('user.transaction.billing.re_print_bill');
                });
                Route::get('/street_lights_maintenance', function () {
                    return view('user.transaction.billing.street_lights_maintenance');
                });
                Route::get('/de_of_streetlight_inventory', function () {
                    return view('user.transaction.billing.de_of_streetlight_inventory');
                });
                Route::get('/de_of_integrated_charges', function () {
                    return view('user.transaction.billing.de_of_integrated_charges');
                });
                Route::get('/de_of_integrated_charges', function () {
                    return view('user.transaction.billing.de_of_integrated_charges');
                });
                Route::get('/bill_cancellation', function () {
                    return view('user.transaction.billing.bill_cancellation');
                });
                Route::get('/summary_of_cancel_bill', function () {
                    return view('user.transaction.billing.summary_of_cancel_bill');
                });
                Route::get('/bill_cancellation', function () {
                    return view('user.transaction.billing.bill_cancellation');
                });
                Route::get('/advance_payment_survey', function () {
                    return view('user.transaction.billing.advance_payment_survey');
                });
                Route::get('/cancel_modify_integration', function () {
                    return view('user.transaction.billing.cancel_modify_integration');
                });
                Route::get('/account_receivable_entry', function () {
                    return view('user.transaction.billing.account_receivable_entry');
                });
                Route::get('/account_receivable_entry', function () {
                    return view('user.transaction.billing.account_receivable_entry');
                });
            });
            Route::prefix('/collection')->group(function() {
                    Route::get('/collection_for_teller_pb', function () {
                        // dd(date('Y-m-d'));
                        $check = collect(DB::table('sales')
                            ->where('teller_user_id',Auth::user()->user_id)
                            ->whereDate('s_bill_date',date('Y-m-d'))
                            ->where('s_cutoff',1)
                            ->get());
                            // dd($check);
                            if($check->first()){
                                return redirect()->route('dashboard');
                            }else{
                                return view('user.transaction.collection.collection_for_teller_pb');
                            }    
                });
                Route::get('/tellers_dcr', function () {
                    return view('user.transaction.collection.tellers_dcr');
                });
                Route::get('/collection_for_teller_nb', function () {
                    return view('user.transaction.collection.collection_for_teller_nb');
                });
                Route::get('/collection_for_multiple_accounts', function () {
                    return view('user.transaction.collection.collection_for_multiple_accounts');
                });
                Route::get('/summary_of_posted_non_bill_collection', function () {
                    return view('user.transaction.collection.summary_of_posted_non_bill_collection');
                });
                Route::get('/print_tellers_daily_collection_report', function () {
                    return view('user.transaction.collection.print_tellers_daily_collection_report');
                });
                Route::get('/print_summary_of_dcr_teller', function () {
                    return view('user.transaction.collection.print_summary_of_dcr_teller');
                });
                Route::get('/post_teller_collection',function(){
                    return view('user.transaction.collection.post_teller_collection');
                });
                Route::get('/void_tellers_collection', function () {
                    return view('user.transaction.collection.void_tellers_collection');
                });
                Route::get('/unposted_collection', function () {
                    return view('user.transaction.collection.unposted_collection');
                });
                Route::get('/posted_collection', function () {
                    return view('user.transaction.collection.posted_collection');
                });
                Route::get('/change_acknowledgement_receipt', function () {
                    return view('user.transaction.collection.change_acknowledgement_receipt');
                });
                Route::get('/print_tellers_non_bill_dcr', function () {
                    return view('user.transaction.collection.print_tellers_non_bill_dcr');
                });
                Route::get('/cashier_dcr', function () {
                    return view('user.transaction.collection.cashier_dcr');
                });
                Route::get('/daily_collection_control_list', function () {
                    return view('user.transaction.collection.daily_collection_control_list');
                });
                Route::get('/summary_of_voided_or', function () {
                    return view('user.transaction.collection.summary_of_voided_or');
                });
                Route::get('/summary_of_double_payment', function () {
                    return view('user.transaction.collection.summary_of_double_payment');
                });
                Route::get('/locate_payor', function () {
                    return view('user.transaction.collection.locate_payor');
                });
                Route::get('/one_time_payment', function () {
                    return view('user.transaction.collection.one_time_payment');
                });
                Route::get('/transfer_collection', function () {
                    return view('user.transaction.collection.transfer_collection');
                });
            });
        });
        Route::prefix('report')->group(function () {
            Route::prefix('meter_reading')->group(function () {
                Route::get('/reading_schedule_by_billing_period', function () {
                    return view('user.report.meter_reading.reading_schedule_by_billing_period');
                });
                Route::get('/reading_schedule_by_reading_date', function () {
                    return view('user.report.meter_reading.reading_schedule_by_reading_date');
                });
                Route::get('/reading_schedule_by_reading_date', function () {
                    return view('user.report.meter_reading.reading_schedule_by_reading_date');
                });
                Route::get('/disconnected_with_bill_by_billing_period', function () {
                    return view('user.report.meter_reading.disconnected_with_bill_by_billing_period');
                });
                Route::get('/disconnected_with_bill_per_route', function () {
                    return view('user.report.meter_reading.disconnected_with_bill_per_route');
                });
                Route::get('/cons_applied_with_average_consumption_by_bp', function () {
                    return view('user.report.meter_reading.cons_applied_with_average_consumption_by_bp');
                });
                Route::get('/cons_applied_with_ave_consumption_by_mrd', function () {
                    return view('user.report.meter_reading.cons_applied_with_ave_consumption_by_mrd');
                });
                Route::get('/unread_unbilled_cons_per_route', function () {
                    return view('user.report.meter_reading.unread_unbilled_cons_per_route');
                });
                Route::get('/unread_unbilled_cons_per_town', function () {
                    return view('user.report.meter_reading.unread_unbilled_cons_per_town');
                });
                Route::get('/cons_with_ff__lasureco_month_by_ff', function () {
                    return view('user.report.meter_reading.cons_with_ff__lasureco_month_by_ff');
                });
                Route::get('/cons_with_ff_town_month', function () {
                    return view('user.report.meter_reading.cons_with_ff_town_month');
                });
                Route::get('/cons_with_zero_kwh_town_month', function () {
                    return view('user.report.meter_reading.cons_with_zero_kwh_town_month');
                });
                Route::get('/time_in_motion_report', function () {
                    return view('user.report.meter_reading.time_in_motion_report');
                });
                Route::get('/summary_of_field_findings', function () {
                    return view('user.report.meter_reading.summary_of_field_findings');
                });
                Route::get('/mrr_1', function () {
                    return view('user.report.meter_reading.mrr_1');
                });
                Route::get('/mrr_2', function () {
                    return view('user.report.meter_reading.mrr_2');
                });
                Route::get('/mrr_inquiry_per_consumer_1', function () {
                    return view('user.report.meter_reading.mrr_inquiry_per_consumer_1');
                });
                Route::get('/mrr_inquiry_per_consumer_2', function () {
                    return view('user.report.meter_reading.mrr_inquiry_per_consumer_2');
                });
                Route::get('/meter_reading_activity_report', function () {
                    return view('user.report.meter_reading.meter_reading_activity_report');
                });
            });
            Route::get('/print_billing_edit_list', function () {
                return view('user.transaction.billing.print_billing_edit_list');
            });
            Route::get('/power_bill_adjustments', function () {
                return view('user.transaction.billing.power_bill_adjustments');
            });
            Route::get('/print_new_bill', function () {
                return view('user.transaction.billing.print_new_bill');
            });
            Route::get('/re_print_bill', function () {
                return view('user.transaction.billing.re_print_bill');
            });
            Route::get('/street_lights_maintenance', function () {
                return view('user.transaction.billing.street_lights_maintenance');
            });
            Route::get('/de_of_streetlight_inventory', function () {
                return view('user.transaction.billing.de_of_streetlight_inventory');
            });
            Route::get('/de_of_integrated_charges', function () {
                return view('user.transaction.billing.de_of_integrated_charges');
            });
            Route::get('/de_of_integrated_charges', function () {
                return view('user.transaction.billing.de_of_integrated_charges');
            });
            Route::get('/bill_cancellation', function () {
                return view('user.transaction.billing.bill_cancellation');
            });
            Route::get('/summary_of_cancel_bill', function () {
                return view('user.transaction.billing.summary_of_cancel_bill');
            });
            Route::get('/bill_cancellation', function () {
                return view('user.transaction.billing.bill_cancellation');
            });
            Route::get('/advance_payment_survey', function () {
                return view('user.transaction.billing.advance_payment_survey');
            });
            Route::get('/cancel_modify_integration', function () {
                return view('user.transaction.billing.cancel_modify_integration');
            });
            Route::get('/account_receivable_entry', function () {
                return view('user.transaction.billing.account_receivable_entry');
            });
            Route::get('/account_receivable_entry', function () {
                return view('user.transaction.billing.account_receivable_entry');
            });
        });
        Route::prefix('maintenance')->group(function () {
            Route::prefix('/security')->group(function(){
                Route::get('/role',function(){
                    return view('user.maintenance.security.role');
                });
                Route::get('/role/add',function(){
                    return view('user.maintenance.security.addRole');
                });
                Route::get('/permission',function(){
                    return view('user.maintenance.security.permission');
                });
                Route::get('/create/user',function(){
                    $roles = Role::all();
                    return view('user.maintenance.security.userIndex',compact('roles'));
                });
            });
            Route::prefix('consumer')->group(function () {
                Route::get('/',function(){
                    return view('user.maintenance.consumer.index');
                });
            });
            Route::get('/collection_for_multiple_accounts', function () {
                return view('user.transaction.collection.collection_for_multiple_accounts');
            });
            Route::get('/summary_of_posted_non_bill_collection', function () {
                return view('user.transaction.collection.summary_of_posted_non_bill_collection');
            });
            Route::get('/print_tellers_daily_collection_report', function () {
                return view('user.transaction.collection.print_tellers_daily_collection_report');
            });
            Route::get('/print_summary_of_dcr_teller', function () {
                return view('user.transaction.collection.print_summary_of_dcr_teller');
            });
            Route::get('/void_tellers_collection', function () {
                return view('user.transaction.collection.void_tellers_collection');
            });
            Route::get('/unposted_collection', function () {
                return view('user.transaction.collection.unposted_collection');
            });
            Route::get('/change_acknowledgement_receipt', function () {
                return view('user.transaction.collection.change_acknowledgement_receipt');
            });
            Route::get('/print_tellers_non_bill_dcr', function () {
                return view('user.transaction.collection.print_tellers_non_bill_dcr');
            });
            Route::get('/cashier_dcr', function () {
                return view('user.transaction.collection.cashier_dcr');
            });
            Route::get('/daily_collection_control_list', function () {
                return view('user.transaction.collection.daily_collection_control_list');
            });
            Route::get('/summary_of_voided_or', function () {
                return view('user.transaction.collection.summary_of_voided_or');
            });
            Route::get('/summary_of_double_payment', function () {
                return view('user.transaction.collection.summary_of_double_payment');
            });
            Route::get('/locate_payor', function () {
                return view('user.transaction.collection.locate_payor');
            });
            Route::get('/transfer_collection', function () {
                return view('user.transaction.collection.transfer_collection');
            });
            
        });
        Route::prefix('/collection')->group(function() {
            Route::get('/collection_for_teller_pb', function () {
                dd('sakdfajgf');
                $check = DB::table('sales')
                    ->where('teller_user_id',Auth::user()->user_id)
                    ->whereDate('s_bill_date',date('Y-m-d'))
                    ->where('s_cutoff',1)
                    ->get();
                    
                    if($check){
                        return redirect()->route('dashboard');
                    }else{
                        return view('user.transaction.collection.collection_for_teller_pb');
                    }    
            });
            Route::get('/collection_for_teller_nb', function () {
                return view('user.transaction.collection.collection_for_teller_nb');
            });
            Route::get('/collection_for_multiple_accounts', function () {
                return view('user.transaction.collection.collection_for_multiple_accounts');
            });
            Route::get('/summary_of_posted_non_bill_collection', function () {
                return view('user.transaction.collection.summary_of_posted_non_bill_collection');
            });
            Route::get('/print_tellers_daily_collection_report', function () {
                return view('user.transaction.collection.print_tellers_daily_collection_report');
            });
            Route::get('/print_summary_of_dcr_teller', function () {
                return view('user.transaction.collection.print_summary_of_dcr_teller');
            });
            Route::get('/void_tellers_collection', function () {
                return view('user.transaction.collection.void_tellers_collection');
            });
            Route::get('/unposted_collection', function () {
                return view('user.transaction.collection.unposted_collection');
            });
            Route::get('/change_acknowledgement_receipt', function () {
                return view('user.transaction.collection.change_acknowledgement_receipt');
            });
            Route::get('/print_tellers_non_bill_dcr', function () {
                return view('user.transaction.collection.print_tellers_non_bill_dcr');
            });
            Route::get('/cashier_dcr', function () {
                return view('user.transaction.collection.cashier_dcr');
            });
            Route::get('/daily_collection_control_list', function () {
                return view('user.transaction.collection.daily_collection_control_list');
            });
            Route::get('/summary_of_voided_or', function () {
                return view('user.transaction.collection.summary_of_voided_or');
            });
            Route::get('/summary_of_double_payment', function () {
                return view('user.transaction.collection.summary_of_double_payment');
            });
            Route::get('/locate_payor', function () {
                return view('user.transaction.collection.locate_payor');
            });
            Route::get('/transfer_collection', function () {
                return view('user.transaction.collection.transfer_collection');
            });
        });
        Route::prefix('/utility')->group(function(){
            // Route::get('/upload_collection_to_server',[UploadCollectionToServerController::class,'index'])->name('utility.upload.collection');
            Route::get('/upload', function () {
                return view('user.utility.upload');
            });
            Route::post('/upload_collection_to_server',[UploadCollectionToServerController::class,'storeToMainServer'])->name('utility.upload.collection.store');
            Route::get('/setup_server',[ServerConnectionController::class,'index'])->name('utility.server.setup');
            Route::post('/setup_server',[ServerConnectionController::class,'store'])->name('ip_address.store');
            Route::get('/setup_server/check',[ServerConnectionController::class,'checkEnabledIp'])->name('ip_address.check');
            Route::get('/setup_server/delete/{server}',[ServerConnectionController::class,'deleteIp'])->name('delete.server_ip');
            Route::patch('/setup_server/update/{server}',[ServerConnectionController::class,'updateIp'])->name('update.server_ip');
            Route::get('/export_sales_data',[ExportSalesDataController::class,'index'])->name('export.sales.data');
            Route::get('/import_sales_data',[ImportSalesDataController::class,'index'])->name('import.sales.data');
        });
    });
});
Route::prefix('print')->group(function () {
    Route::get('/print_vat_sales_per_town', function () {
        return view('print.print_vat_sales_per_town');
    })->name('print_vat_sales_per_town');
    Route::get('/print_collection_report_on_vat_per_date', function () {
        return view('print.print_collection_report_on_vat_per_date');
    })->name('print_collection_report_on_vat_per_date');
    Route::get('/print_summary_of_sales_unbundled_for_lgus', function () {
        return view('print.print_summary_of_sales_unbundled_for_lgus');
    })->name('print_summary_of_sales_unbundled_for_lgus');
    Route::get('/print_summary_of_sales_unbundled', function () {
        return view('print.print_summary_of_sales_unbundled');
    })->name('print_summary_of_sales_unbundled');
    Route::get('/print_bill_deposit_report', function () {
        return view('print.print_bill_deposit_report');
    })->name('print_bill_deposit_report');
    Route::get('/print_lgu_consumer_listing', function () {
        return view('print.print_lgu_consumer_listing');
    })->name('print_lgu_consumer_listing');
    Route::get('/print_unbundled_collection_report', function () {
        return view('print.print_unbundled_collection_report');
    })->name('print_unbundled_collection_report');
    Route::get('/print_summary_of_sales_collected_ar_fit_all', function () {
        return view('print.print_summary_of_sales_collected_ar_fit_all');
    })->name('print_summary_of_sales_collected_ar_fit_all');
	Route::get('/print_operating_revenue', function () {
        return view('print.print_operating_revenue');
    })->name('print_operating_revenue');
    Route::get('/print_monthly_cashiers_dcr_so', function () {
        return view('print.print_monthly_cashiers_dcr_so');
    })->name('print_monthly_cashiers_dcr_so');
    Route::get('/print_cashiers_dcr_collection', function () {
        return view('print.print_cashiers_dcr_collection');
    })->name('print_cashiers_dcr_collection');
    Route::get('/print_vat_sales_per_consumer_type', function () {
        return view('print.print_vat_sales_per_consumer_type');
    })->name('print_vat_sales_per_consumer_type');
    Route::get('/print_reconnection_report', function () {
        return view('print.print_reconnection_report');
    })->name('print_reconnection_report');
    Route::get('/print_collection_summary_per_date', function () {
        return view('print.print_collection_summary_per_date');
    })->name('print_collection_summary_per_date');
    Route::get('/print_collection_summary', function () {
        return view('print.print_collection_summary');
    })->name('print_collection_summary');
    Route::get('/print_summary_of_metered_and_unmetered_consumers', function () {
        return view('print.print_summary_of_metered_and_unmetered_consumers');
    })->name('print_summary_of_metered_and_unmetered_consumers');
    Route::get('/print_summary_of_newly_entered_consumers', function () {
        return view('print.print_summary_of_newly_entered_consumers');
    })->name('print_summary_of_newly_entered_consumers');
    Route::get('/print_total_collection_report', function () {
        return view('print.print_total_collection_report');
    })->name('print_total_collection_report');
    Route::get('/print_total_collection_report_non_bill', function () {
        return view('print.print_total_collection_report_non_bill');
    })->name('print_total_collection_report_non_bill');
    Route::get('/print_total_collection_report_all_bill', function () {
        return view('print.print_total_collection_report_all_bill');
    })->name('print_total_collection_report_all_bill');
    Route::get('/print_sales_vs_collection', function () {
        return view('print.print_sales_vs_collection');
    })->name('print_sales_vs_collection');
    Route::get('/print_sales_versus_collection', function () {
        return view('print.print_sales_versus_collection');
    })->name('print_sales_versus_collection');
    Route::get('/print_list_of_remarked_consumers', function () {
        return view('print.print_list_of_remarked_consumers');
    })->name('print_list_of_remarked_consumers');
    Route::get('/print_pending_consumers', function () {
        return view('print.print_pending_consumers');
    })->name('print_pending_consumers');
    Route::get('/print_summary_of_changed_status', function () {
        return view('print.print_summary_of_changed_status');
    })->name('print_summary_of_changed_status');
	Route::get('/print_summary_of_changed_consumer_type', function () {
        return view('print.print_summary_of_changed_consumer_type');
    })->name('print_summary_of_changed_consumer_type');
    Route::get('/print_list_of_consumer_for_disconnection', function () {
        return view('print.print_list_of_consumer_for_disconnection');
    })->name('print_list_of_consumer_for_disconnection');
    Route::get('/print_master_list', function () {
        return view('print.print_master_list');
    })->name('print_master_list');
    Route::get('/print_posted_collection', function () {
        return view('print.print_posted_collection');
    })->name('print_posted_collection');
	Route::get('/print_billing_rates', function () {
        return view('print.print_billing_rates');
    })->name('print_billing_rates');
    Route::get('/print_edit_billing_list', function () {
        return view('print.print_edit_billing_list');
    })->name('print_edit_billing_list');
    Route::get('/print_mrr_1', function () {
        return view('print.print_mrr_1');
    })->name('print_mrr_1');
    Route::get('/print_mrr_inquiry_per_consumer_2', function () {
        return view('print.print_mrr_inquiry_per_consumer_2');
    })->name('print_mrr_inquiry_per_consumer_2');
    Route::get('/print_summary_of_canceled_bills', function () {
        return view('print.print_summary_of_canceled_bills');
    })->name('print_summary_of_canceled_bills');
    Route::get('/print_tellers_daily_collection_report', function () {
        return view('print.print_tellers_daily_collection_report');
    })->name('print_tellers_daily_collection_report');
    Route::get('/print_summary_of_adjusted_bill', function () {
        return view('print.print_summary_of_adjusted_bill');
    })->name('print_summary_of_adjusted_bill');
    Route::get('/print_summary_of_bills_additional', function () {
        return view('print.print_summary_of_bills_additional');
    })->name('print_summary_of_bills_additional');
    Route::get('/print_adjusted_bill_dtl', function () {
        return view('print.print_adjusted_bill_dtl');
    })->name('print_adjusted_bill_dtl');
    Route::get('/print_cashiers_dcr', function () {
        return view('print.print_cashiers_dcr');
    })->name('print_cashiers_dcr');
    Route::get('/print_increase_in_consumption', function () {
        return view('print.print_increase_in_consumption');
    })->name('print_increase_in_consumption');
    Route::get('/print_decrease_in_consumption', function () {
        return view('print.print_decrease_in_consumption');
    })->name('print_decrease_in_consumption');
    Route::get('/print_big_loads', function () {
        return view('print.print_big_loads');
    })->name('print_big_loads');
    Route::get('/print_summary_of_universal_charges', function () {
        return view('print.print_summary_of_universal_charges');
    })->name('print_summary_of_universal_charges');
    Route::get('/print_vat_sales_collection_per_town', function () {
        return view('print.print_vat_sales_collection_per_town');
    })->name('print_vat_sales_collection_per_town');
    Route::get('/print_customer_sales_per_charges', function () {
        return view('print.print_customer_sales_per_charges');
    })->name('print_customer_sales_per_charges');
    Route::get('/print_summary_nb_collection', function () {
        return view('print.print_summary_nb_collection');
    })->name('print_summary_nb_collection');
    Route::get('/print_cashiers_dcr', function () {
        return view('print.print_cashiers_dcr');
    })->name('print_cashiers_dcr');
    Route::prefix('collection')->group(function () {
        Route::get('/collectionOR', function () {
            return view('print.collection.collectionOR');
        })->name('PBOR');
        Route::get('/newCollectionOR', function () {
            return view('print.collection.newCollectionOR');
        })->name('newOR');
    });
    Route::prefix('collection')->group(function () {
        Route::get('/chequeOR', function () {
            return view('print.collection.chequeOR');
        })->name('chequeOR');
        Route::get('/newChequeOR', function () {
            return view('print.collection.newChequeOR');
        })->name('newChequeOR');
        Route::get('/collectionORSave', function () {
            return view('print.collection.collectionORSave');
        })->name('ORsave');
        Route::get('/unpostedCol', function () {
            return view('print.collection.unpostedCol');
        })->name('uncol');
        // Route::get('/unpostedCol', function () {
        //     return view('print.collection.unpostedCol');
        // })->name('uncol');
        Route::get('/ewalletOR', function () {
            return view('print.collection.ewalletOR');
        })->name('ewalletOR');
        Route::get('/newEwalletOR', function () {
            return view('print.collection.newEwalletOR');
        })->name('newEwalletOR');
	    Route::get('/print_collection_report', function () {
            return view('print.collection.print_collection_report');
        })->name('print_collection_report');
        Route::get('/print_collection_report_per_route', function () {
            return view('print.collection.print_collection_report_per_route');
        })->name('print_collection_report_per_route');
        Route::get('/print_summary_of_non_bill_collection', function () {
            return view('print.collection.print_summary_of_non_bill_collection');
        })->name('print_summary_of_non_bill_collection');
    });
    Route::prefix('billing')->group(function () {
        Route::get('/print_general_detail_report', function () {
            return view('print.billing.print_general_detail_report');
        })->name('print_general_detail_report');
        Route::get('/print_list_of_member_consumer_per_kwh_used', function () {
            return view('print.billing.print_list_of_member_consumer_per_kwh_used');
        })->name('print_list_of_member_consumer_per_kwh_used');
        Route::get('/print_general_summary_report', function () {
            return view('print.billing.print_general_summary_report');
        })->name('print_general_summary_report');
        Route::get('/print_power_sales_per_route', function () {
            return view('print.billing.print_power_sales_per_route');
        })->name('print_power_sales_per_route');
        Route::get('/print_summary_of_bills', function () {
            return view('print.billing.print_summary_of_bills');
        })->name('print_summary_of_bills');
        Route::get('/print_summary_of_bills_unbundled', function () {
            return view('print.billing.print_summary_of_bills_unbundled');
        })->name('print_summary_of_bills_unbundled');
        Route::get('/print_advance_power_bill_applied_to_bill', function () {
            return view('print.billing.print_advance_power_bill_applied_to_bill');
        })->name('print_advance_power_bill_applied_to_bill');
        Route::get('/print_lifeline_consumer_detailed', function () {
            return view('print.billing.print_lifeline_consumer_detailed');
        })->name('print_lifeline_consumer_detailed');
        Route::get('/print_summary_of_unread_unbilled_consumer', function () {
            return view('print.billing.print_summary_of_unread_unbilled_consumer');
        })->name('print_summary_of_unread_unbilled_consumer');
        Route::get('/print_summary_of_consumer_per_kwh_used', function () {
            return view('print.billing.print_summary_of_consumer_per_kwh_used');
        })->name('print_summary_of_consumer_per_kwh_used');
        Route::get('/print_sales_per_type_with_consumer_name', function () {
            return view('print.billing.print_sales_per_type_with_consumer_name');
        })->name('print_sales_per_type_with_consumer_name');
        Route::get('/print_consumer_data', function () {
            return view('print.billing.print_consumer_data');
        })->name('print_consumer_data');
        Route::get('/print_summary_of_bills_amount_issued', function () {
            return view('print.billing.print_summary_of_bills_amount_issued');
        })->name('print_summary_of_bills_amount_issued');
        Route::get('/print_summary_of_sales_coverage', function () {
            return view('print.billing.print_summary_of_sales_coverage');
        })->name('print_summary_of_sales_coverage');
        Route::get('/print_summary_of_Energy_Sales_per_Consumer_Type', function () {
            return view('print.billing.print_summary_of_Energy_Sales_per_Consumer_Type');
        })->name('print_summary_of_Energy_Sales_per_Consumer_Type');
        Route::get('/print_summary_of_revenue_town', function () {
            return view('print.billing.print_summary_of_revenue_town');
        })->name('print_summary_of_revenue_town');
        Route::get('/print_summary_of_revenue_consumer_type', function () {
            return view('print.billing.print_summary_of_revenue_consumer_type');
        })->name('print_summary_of_revenue_consumer_type');
        Route::get('/print_lifeline_discount_per_area', function () {
            return view('print.billing.print_lifeline_discount_per_area');
        })->name('print_lifeline_discount_per_area');
        Route::get('/print_lifeline_discount_per_town', function () {
            return view('print.billing.print_lifeline_discount_per_town');
        })->name('print_lifeline_discount_per_town');
        Route::get('/print_lifeline_discount_per_route', function () {
            return view('print.billing.print_lifeline_discount_per_route');
        })->name('print_lifeline_discount_per_route');
        Route::get('/powerbill', function () {
            return view('print.billing.powerbill');
        })->name('powerbill');
        Route::get('/print_ledger_report', function () {
            return view('print.billing.print_ledger_report');
        })->name('PLR');
    });
    Route::prefix('report')->group(function () {
        Route::get('/lncdpr', function () {
            return view('print.report.lncdpr');
        })->name('lConsDetailedPerRoute');
        Route::get('/print_online_report', function () {
            return view('print.report.print_online_report');
        })->name('onlinepayment');
    });
    Route::prefix('report')->group(function () {
        Route::get('/sscafa', function () {
            return view('print.report.sscafa');
        })->name('SFitAll');
    });
    Route::prefix('report')->group(function () {
        Route::get('vspct', function () {
            return view('print.report.vspct');
        })->name('SperConsumer');
        Route::prefix('ISD')->group(function () {
            Route::get('/print_consumer_listing', function () {
                return view('print.report.ISD.print_consumer_listing');
            })->name('PCL');
            Route::get('/print_kwh_report', function () {
                return view('print.report.ISD.print_kwh_report');
            })->name('print_kwh_report');
            Route::get('/print_consumer_listing', function () {
                return view('print.report.ISD.print_consumer_listing');
            })->name('PCL');
            Route::get('/penaltyPrint', function () {
                return view('print.report.ISD.penaltyPrint');
            })->name('PP');
        });
        Route::get('/print_delinquent', function () {
            return view('print.report.Accounting.print_delinquent');
        })->name('PD');
        Route::get('/print_actual_vat', function () {
            return view('print.report.Accounting.print_actual_vat');
        })->name('PAV');
        Route::get('/print_prompt_payor', function () {
            return view('print.report.Accounting.print_prompt_payor');
        })->name('PPP');
        Route::prefix('collection')->group(function () {
            Route::get('print_collection_for_month_per_town', function () {
                return view('print.report.collection.print_collection_for_month_per_town');
            })->name('print_collection_for_month_per_town');
        });
    });
    Route::prefix('transaction')->group(function () {
        Route::prefix('meterreading')->group(function () {
            Route::get('listofsoa', function () {
                return view('print.transaction.meterreading.listofsoa');
            })->name('listofsoa');
            Route::get('soaakc', function () {
                return view('print.transaction.meterreading.soaakc');
            })->name('soaakc');
        });
    });
    Route::prefix('maintenance')->group(function () {
        Route::get('cons_ledger_inquiry', function () {
            return view('print.maintenance.cons_ledger_inquiry');
        })->name('cdinquiry');
        Route::get('cons1_ledger_inquiry', function () {
            return view('print.maintenance.cons1_ledger_inquiry');
        })->name('cdinquiry1');
        Route::get('print_complaint', function () {
            return view('print.maintenance.print_complaint');
        })->name('print.complaint');
        Route::get('print_sketch/{id}',[ComplaintController::class,'sketh'])->name('print.sketch');
    });
});

Route::get('/test',function(){
    $query = DB::table('substation_code')->select('sc_id','sc_desc');
    
});
Route::get('/print_aging',function(){
    return view('print.report.print_aging');
})->name('print.aging');
//Auth::routes();
Route::get('v1/area/datatable',[App\Http\Controllers\Api\AreaCodeController::class,'getAreaCode'])->name('api.area.code');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/export_sales',[ExportSalesDataController::class,'exportSales'])->name('export.sales');
Route::post('/import_sales',[ImportSalesDataController::class,'store'])->name('import.sales');
Route::get('/pending',[ConsumerPendingController::class,'getPendingConsumer'])->name('consumers.pending');
Route::get('/consumer_index',[DeleteConsumerController::class,'getConsumers'])->name('consumers.index');
Route::post('/delete_consumer',[DeleteConsumerController::class,'deleteConsumer'])->name('remove.consumer');
Route::post('/destroy_consumer',[DeleteConsumerController::class,'destroyConsumer'])->name('destroy.consumer');
Route::post('/supervisory',[DeleteConsumerController::class,'supervisory'])->name('verify.supervisory');
Route::get('/user/maintenance/complaintShow',[ComplaintController::class,'getPendingConsumerC'])->name('list.show');
Route::get('/user/maintenance/complaintshow',[ComplaintController::class,'getPendingConsumerC'])->name('list1.show');
Route::get('/user/maintenance/complaint/show',[ComplaintController::class,'index'])->name('complaint.show');
Route::get('/user/maintenance/complaint/store',[ComplaintController::class,'complaintCategoryindex'])->name('complaint.category');
Route::get('/user/maintenance/complaintstore',[ComplaintController::class,'complaintCategory'])->name('complaint.category1');
Route::post('/user/maintenance/complaintstore',[ComplaintController::class,'complaintCategoryStore'])->name('category.store');
Route::post('/user/maintenance/complaintstore/subcategory',[ComplaintController::class,'complaintSubCategoryStore'])->name('subcategory.store');
Route::get('/user/maintenance/complaintstore/subcategory/show',[ComplaintController::class,'complaintSubCategory'])->name('subcategory.show');
Route::get('/user/maintenance/complaint/show/info/{id}',[ComplaintController::class,'complaintListInfo'])->name('info.show');
Route::post('/user/maintenance/complaint/show/Export',[ComplaintController::class,'exportComplaint'])->name('export.complaint');
Route::post('/user/maintenance/complaint/update',[ComplaintController::class,'updateComplaint'])->name('update.complaint');
Route::post('/user/maintenance/complaintStore',[ComplaintController::class,'store'])->name('complaint.store');
Route::post('/user/report/isd/kwh_report/',[BillingReportController::class, 'kwhReport'])->name('kwh.report');
Route::post('/user/report/isd/consumer_per_kwh_used/',[BillingReportController::class, 'perKwhUsed'])->name('per.kwh.used');

Route::get('/test/server',function(){
    $response = null;
    $server = Server::select('ip_address')->first();
system("ping ".$server['ip_address']."", $response);
    if($response == 0)
    {
        echo "connceted!";
    }
});
Route::get('/test/new',function(){
    $s = Sales::where('s_bill_no','21014343350150')->first();
    dd($s->ref_date);
});
Route::get('/test/decrypt/{name?}', function ($name = '') { 
    dd(Crypt::decryptString($name));
});

Route::get('/duplicate/sales',function(){
    $arr = array();
    $s = DB::table('sales')->select(DB::raw('s_bill_no,cm_id , COUNT(s_bill_no) as count'))
    ->groupBy('s_bill_no')
    ->where('server_added','=',1)
    ->havingRaw('count > 1')->get();
    dd($s);
    foreach ($s as $key) {
        $route = DB::table('cons_master')->where('cm_id',$key->cm_id)->first();
        $rcdesc = DB::table('route_code')->where('rc_id',$route->rc_id)->first();
        array_push($arr,$rcdesc->rc_code);
    }
    $u = array_unique($arr);
    dd($u);
});

Route::get('/remove/ewallet',function(){
    $a = array();
    $sales = Sales::select('s_or_num','s_bill_date','e_wallet_added')->whereDate('s_bill_date','>=','2022-02-17')->where('e_wallet_added','!=',null)->get();
    dd($sales);
    foreach($sales as $s){
        $ewl = EWALLET_LOG::where('ewl_or',$s->s_or_num)->where('ewl_status','U')->get();
        foreach($ewl as $e){
            array_push($a,$e);
        }
    }
    dd($a);
});
Route::get('/test/dating',function(){
    $consumers = Sales::query()->whereDate('s_bill_date','2022-09-12')->whereDate('s_bill_date','2022-09-16')
    ->where('teller_user_id',44);
    dd($consumers);
});
Route::get('/test/dat',function(){
    $consumers = Sales::query()->where('s_bill_date','=','2022-09-12')->where('s_bill_date','<=','2022-09-16')
    ->where('teller_user_id',44)->orderBy('s_bill_date','desc')->first();
    dd($consumers);
});
Route::get('/test/consumerss',function(){
    $c = Consumer::where('cm_account_no',4343430121)->get();
    dd($c);
});
