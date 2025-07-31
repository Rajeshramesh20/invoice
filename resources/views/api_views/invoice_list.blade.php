<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice List</title> 
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
		/>
		<!-- Include Flatpickr -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="{{asset('css/invoice-table.css')}}">

	<script>
		const token = localStorage.getItem('token');
		if(!token){
			window.location.replace('./api/login')
		}else{
			window.addEventListener('DOMContentLoaded',()=>{
				document.body.style.display='block';
			});
		}

	</script>
</head>
<body>
	<header>
		<img src="{{ asset('/images/twigik.png') }}" class="twigikImage" alt="Twigik Logo">
		<div>
			<!-- <a href="" class="create-note">Credit Note</a>
				<a href="" class="group-invoice">Group Invoice</a> -->
				<a href="/api/company/form" class="create">Add Company</a> 
				<a href="/api/employeeList" class="create">Employee</a>
				<a href="/api/customer/list" class="create">view customer</a>
			<a href="/api/invoice" class="create"><i class="bi bi-plus"></i>Create</a>
			<button class='logout btn' id="logoutBtn">Logout</button>
		</div>
	</header>

	<div>
		<div class="invoice-search">
			<p>Sales Invoice List</p>
			<i class="fa-solid fa-magnifying-glass"></i>
		</div>

		<form id="formSubmit">
			<div class="form-row">
				<div class="form-group">
					<label for="startDate">Start Date</label>
					<input type="text" id="startDate" name="startDate" placeholder="DD-MM-YYYY"/>
				</div>

				<div class="form-group">
					<label for="endDate">End Date</label>
					<input type="text" name="endDate" id="endDate" placeholder="DD-MM-YYYY">
				</div>

				<div class="form-group">
					<label for="customer_id">Customer Name</label>
					<select name="customer_id" id="customer_id">
						<option value="" disabled selected hidden>Select Customer Name</option>
					</select>
				</div>

				<div class="form-group">
					<label for="invoice_no">Invoice Number</label>
					<input type="text" name="invoice_no" id="invoice_no" placeholder="Enter Invoice Number">
				</div>

				<div class="form-group">
					<label for="invoice_status">Status</label>
					<select name="invoice_status" id="invoice_status">
						<option value="" disabled selected hidden>Select Status</option>
					</select>
				</div>
				<div class="form-group">
					<label for="email_status">Email Status</label>
					<select name="email_status" id="email_status">
						<option value="" disabled selected hidden>Select Email Status</option>
						<option value="send">Send</option>
						<option value="not_yet_send">Not yet send</option>
						<option value="failed">Failed</option>
						<option value="not_applicable">Not applicable</option>
					</select>
				</div>	
			</div>
			<hr>
			<div class="reset">
				<a href="/api/invoice/list" class="clear">Reset</a>
				<input type="submit" name="search" value="Search" class="search">
			</div>
	</div>
	</form>
		<div class="theader" style="text-align:right;border-radius:10px">
			<button class="export" id="exportBtn">Export</button>
		</div>

	<table>
		<thead>
			<tr>
				<th>Invoice Number</th>
				<th>Invoice Date</th>
				<th>Due Date</th>
				<th> ₹ Total Value</th>
				<th> ₹ Balance</th>
				<th>Status</th>
				<th>Email Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="involiceList"></tbody>
	</table>
	<!-- Pagination -->
	<div id="paginateButton" class="pagination"></div>

        {{-- modal --}}
    </div>
	<!-- Custom alert Box -->
	<div id="customAlert" class="custom-alert">
	  <p id="alertMessage"></p>
	  <button onclick="closeAlert()" class="alertBtn">OK</button>
	</div>

	<!-- change status use Popup -->
	<!-- Modal Overlay -->
<div id="modalOverlay">
  <div id="changeStatus">
	<span id="closePopup">&times;</span>
	<form id="status">
		<label>Select Status</label>
	  <label><input type="radio" name="invoice_status" value="1"> Draft</label>
	  <label><input type="radio" name="invoice_status" value="2"> Finalised</label>
	  <label><input type="radio" name="invoice_status" value="3"> Partially Paid</label>
	  <label><input type="radio" name="invoice_status" value="4"> Paid</label>
	  <input type="submit" name="submit" id="statusSubmit" value="Update">
	</form>
  </div>
</div>

	<!-- Custom alert Box for Partially Paid -->
		<div id="paidAlert" class="custom-alert paid" >
				<span id="closePopup" onclick="closePaidAlert()">&times;</span>
				<label for="partiallyPaid">Enter Amount</label>
		  	<input type="number" name="partiallyPaid" id="paid_amount" placeholder="Enter Amount">
		  	<p id="paid_amount_err" class="error"></p>
		  	<button onclick="updatePaidAmount(selectedInvoiceId)" class="alertBtn">Enter</button>
		</div>
		{{-- <div id="container" style="height: 300px; width: 350px;"></div> --}}
<div style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 20px;">
    <div id="overallDonut" style="width: 400px; height: 300px;"></div>
    <div id="amountColumn" style="width: 350px; height: 300px;"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/js/invoiceListV1.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
{{-- <script>
	// const token = localStorage.getItem('token');
		
 document.addEventListener('DOMContentLoaded', function () {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://127.0.0.1:8000/api/invoicechart', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.status && response.data.overall) {
                    const data = response.data.overall;
                    const total = parseFloat(data.total);
                    const paid = parseFloat(data.paid);
                    const unpaid = parseFloat(data.unpaid_total);
                    const invoiceCount = data.count;

                    Highcharts.chart('container', {
                        chart: {
                            type: 'pie',
                            custom: {},
                            events: {
                              render() {
    const chart = this;
    const series = chart.series[0];

    let customLabel = chart.options.chart.custom.label;

    const labelHTML = `
        <div style="text-align: center; font-size: 14px;">
            Invoices<br/>
            <span style="font-weight: bold; font-size: 18px;">${invoiceCount}</span><br/>
            ₹${total.toLocaleString()}
        </div>
    `;

    if (!customLabel) {
        customLabel = chart.options.chart.custom.label =
            chart.renderer.label(labelHTML, 0, 0, null, null, null, true) // `true` = useHTML
                .css({
                    width: '120px'
                })
                .add();
    } else {
        customLabel.attr({ text: labelHTML });
    }

    const labelBBox = customLabel.getBBox();
    const x = series.center[0] + chart.plotLeft - (labelBBox.width / 2);
    const y = series.center[1] + chart.plotTop - (labelBBox.height / 2);

    customLabel.attr({ x, y });
}

                            }
                        },
                        title: {
                            text: 'Overall Invoice Summary'
                        },
                        tooltip: {
                            pointFormat: '{point.name}: <b>₹{point.y}</b> ({point.percentage:.0f}%)'
                        },
                        plotOptions: {
                            pie: {
                                innerSize: '70%',
                                dataLabels: [{
                                    enabled: true,
                                    format: '{point.name}: ₹{point.y}'
                                }]
                            }
                        },
                        series: [{
                            name: 'Amount',
                            colorByPoint: true,
                            data: [
                                { name: 'Paid', y: paid },
                                { name: 'Unpaid', y: unpaid }
                            ]
                        }]
                    });
                }
            }
        };
        xhr.send();
    });
</script> --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://127.0.0.1:8000/api/invoicechart', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const res = JSON.parse(xhr.responseText);

            if (res.status && res.data) {
                const overall = res.data.overall;
                const thisMonth = res.data.thisMonth;
                const unpaidMonth = res.data.monthly_unpaid['2025-07'] || { total_unpaid: 0 };

                const total = overall.total;
                const invoiceCount = overall.count;

                Highcharts.chart('overallDonut', {
                    chart: {
                        type: 'pie',
                        events: {
                            render() {
                                const chart = this;
                                const series = chart.series[0];

                                const labelHTML = `
                                    <div style="text-align: center;">
                                        <div style="font-size: 13px;">Invoices</div>
                                        <div style="font-weight: bold; font-size: 18px;">${invoiceCount}</div>
                                        <div style="font-size: 13px;">₹${total.toLocaleString()}</div>
                                    </div>
                                `;

                                // Create or update label
                                if (!chart.customLabel) {
                                    chart.customLabel = chart.renderer.label(labelHTML, 0, 0, null, null, null, true)
                                        .css({ width: '120px' })
                                        .attr({ zIndex: 5 })
                                        .add();
                                } else {
                                    chart.customLabel.attr({ text: labelHTML });
                                }

                                // Center the label
                                const labelBBox = chart.customLabel.getBBox();
                                const centerX = series.center[0] + chart.plotLeft;
                                const centerY = series.center[1] + chart.plotTop;
                                const labelX = centerX - (labelBBox.width / 2);
                                const labelY = centerY - (labelBBox.height / 2);

                                chart.customLabel.attr({ x: labelX, y: labelY });
                            }
                        }
                    },
                    title: { text: 'Overall Invoice Status' },
                  tooltip: {
                        pointFormat: '{point.name}: ₹{point.y:.2f} ({point.percentage:.0f}%)'
                    },
                    plotOptions: {
                        pie: {
                            innerSize: '60%',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: ₹{point.y:.2f}'  
                            }
                        }
                    },
                    series: [{
                        name: 'Amount',
                        colorByPoint: true,
                        data: [
                            { name: 'Paid', y: overall.paid },
                            { name: 'Unpaid', y: overall.unpaid_total }
                        ]
                    }]
                });

                // Column Chart
                Highcharts.chart('amountColumn', {
                    chart: { type: 'column' },
                    title: { text: 'Paid vs Unpaid: Overall vs This Month' },
                    xAxis: { categories: ['Paid', 'Unpaid'] },
                    yAxis: {
                        title: { text: 'Amount (₹)' }
                    },
                  tooltip: {
                            shared: true,
                            valuePrefix: '₹',
                            valueDecimals: 2 
                        },
                        plotOptions: {
                            column: {
                                dataLabels: {
                                    enabled: true,
                                    format: '₹{y:.2f}' 
                                }
                            }
                        },
                    series: [
                        { name: 'Overall', data: [overall.paid, overall.unpaid_total] },
                        { name: 'This Month', data: [thisMonth.paid || 0, unpaidMonth.total_unpaid || 0] }
                    ]
                });
            }
        }
    };
    xhr.send();
});
</script>



</body>

</html>