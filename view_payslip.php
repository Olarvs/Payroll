<?php include 'db_connect.php' ?>
<?php include 'header.php' ?>
<?php
	$payroll=$conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id  where p.id=".$_GET['id']);
	foreach ($payroll->fetch_array() as $key => $value) {
		$$key = $value;
	}
	$pay = $conn->query("SELECT * FROM payroll where id = ".$payroll_id)->fetch_array();
	$pt = array(1=>"Monhtly",2=>"Semi-Monthly");
?>


<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" style ="position:absolute; top:-8%; left: 82%;" type="button" data-dismiss="modal">Close</button>
			
<div class="contriner-fluid">
	<div class="col-md-12">
		<h5><b><small>Employee ID :</small><?php echo $employee_no ?></b></h5>
		<h4><b><small>Name: </small><?php echo ucwords($ename) ?></b></h4>
		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<p><b>Payroll Ref : <?php echo $pay['ref_no'] ?></b></p>
				<p><b>Payroll Range : <?php echo date("M d, Y",strtotime($pay['date_from'])). " - ".date("M d, Y",strtotime($pay['date_to'])) ?></b></p>
				<p><b>Payroll type : <?php echo $pt[$pay['type']] ?></b></p>
			</div>
			<div class="col-md-6">
				<p><b>Days of Absent : <?php echo $absent ?></b></p>
				<p><b>Tardy/Undertime (mins) : <?php echo $late ?></b></p>
				<p><b>Total Allowance Amount : <?php echo number_format($allowance_amount,2) ?></b></p>
				<p><b>Total Deduction Amount : <?php echo number_format($deduction_amount,2) ?></b></p>
				<p><b>Net Pay : <?php echo number_format($net,2) ?></b></p>
			</div>
		</div>
	

		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Allowances</b></span>
						
					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
                            $total = 0.00;
							$all_qry = $conn->query("SELECT * from allowances ");
							$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
							while($row=$all_qry->fetch_assoc()):
								$all_arr[$row['id']] = $row['allowance'];
						 	endwhile; 
						 	foreach(json_decode($allowances) as $k => $val):
                                $total = floatval($total) + floatval($val->amount);
							?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
						   <?php echo $all_arr[$val->aid] ?> Allowance
					    <span class="badge badge-primary badge-pill" style
                        ="border: none"><?php echo number_format($val->amount,2) ?></span>
					  </li>
						  
						<?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
						   Total
					    <span class="badge badge-primary badge-pill" style
                        ="border: none"><?php echo number_format($total,2); ?></span>
					  </li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Deductions</b></span>
						
					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
                            $total2 = 0.00;
							$all_qry = $conn->query("SELECT * from deductions ");
							$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
							while($row=$all_qry->fetch_assoc()):
								$ded_arr[$row['id']] = $row['deduction'];
						 	endwhile; 
						 	foreach(json_decode($deductions) as $k => $val):
                                $total2 = floatval($total2) + floatval($val->amount);
							?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
						   <?php echo $ded_arr[$val->did] ?>
					    <span class="badge badge-primary badge-pill" style
                        ="border: none"><?php echo number_format($val->amount,2) ?></span>
					  </li>
						  
						<?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
						   Total
					    <span class="badge badge-primary badge-pill" style
                        ="border: none"><?php echo number_format($total2,2); ?></span>
					  </li>
						</ul>
					</div>
				</div>
			</div>
		
		</div>
	</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-6" style="margin-left: 6%;">
			<button class="btn btn-success btn-sm btn-block col-md-2 float-right" type="button" id="print_btn1"><span class="fa fa-print"></span> Print</button>
		</div>
	</div>
		
</div>
<style type="text/css">
	.list-group-item>span>p{
		margin:unset;
	}
	.list-group-item>span>p>small{
		font-weight: 700
	}
	#uni_modal .modal-footer{
		display: none;
	}
	.alist,.dlist{
		width: 100%
	}
</style>
<script>
	$(document).ready(function(){
		$('#print_btn1').click(function(){
				var nw = window.open("print_payslip.php?id=<?php echo $_GET['id'] ?>","_blank","height=500,width=800")
				setTimeout(function(){
					nw.print()
					setTimeout(function(){
						nw.close()
						},500)
				},1000)
			})
	})

</script>