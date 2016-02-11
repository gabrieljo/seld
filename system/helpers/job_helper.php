<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('calculate_profit'))
{
	function calculate_profit($job_id = 0)
	{
		$CI =& get_instance();
		
		$CI->load->model('client_model', 'cm');
		$CI->load->model('log_model', 'log');

		// Configs
		$flat_rate 		= $CI->config_model->get_config('flat_rate');
		$amount_prefix 	= $CI->config_model->get_config('amount_prefix');
		$amount_suffix 	= $CI->config_model->get_config('amount_suffix');

		// Defaults 
		$total_invoices = $total_investments = $total_expenses = 0; // Set every total to zero [0]

		// Data 
		$users 			= $CI->log->job_users($job_id);
		$total_invoices = $CI->cm->get_total_invoice($job_id);
		$total_expenses = $CI->cm->get_total_expense($job_id);

		// Calculation # TOTAL INVESTMENTS
		foreach ($users->result() as $dt):
			$hours 				= floor((intval($dt->total_hours)/60)*100)/100;
			$amt 				= intval($dt->rate) == 0 ? $flat_rate : $dt->rate;
            $inv 				= intval($amt) * $hours;
            $total_investments += $inv;
		endforeach;

		// Profit
		$profit = $total_invoices - $total_investments - $total_expenses;
		
		// Update to DB
		$data['_cal_profit'] 		= $profit;
		$data['_cal_investments'] 	= $total_investments;
		$data['_cal_invoices'] 		= $total_invoices;
		$data['_cal_expenses'] 		= $total_expenses;
		$data['_cal_date'] 			= date('Y-m-d H:i:s');
		$CI->cm->save_job($job_id, $data);
	}
}

if (! function_exists('calculate_profit_log')){
	function calculate_profit_log($log_id=0){
		$CI =& get_instance();

		$CI->load->model('log_model', 'log');
		$log = $CI->log->find_log_by_id($log_id);

		calculate_profit($log->job_id);
	}
}

