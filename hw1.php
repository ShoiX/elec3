<?php require('template/header.php'); ?>
<div class="main container-fluid">
	<h1>MCLauren Series</h1>
	<select id="selector">
		<option value='content1' selected>cos x</option>
		<option value="content2">sin x</option>
		<option value="content3">ln(x+1)</option>
	</select>

	<!-- START OF CONTENT1 -->
	<div class="content" id="content1">
		<img src="lib/img/cos_formula.png">
		<img src="lib/img/cos.png">
		<div>
			<p style="color: gray">cos(x) vs its McLarin Series up to the 50th term (x from -6.6 to 6.6 with interval of 0.066)</p>
		</div>
	</div>
	<!-- START OF CONTENT 2 -->
	<div class="content hidden" id="content2">
		<img src="lib/img/sin_formula.png">
		<img src="lib/img/sin.png">
		<div>
			<p style="color: gray">sin(x) vs its McLarin Series up to the 50th term (x from -6.6 to 6.6 with interval of 0.066)</p>
		</div>
	</div>

	<!-- START OF CONTENT3 -->
	<div class="content hidden" id="content3">
		<img src="lib/img/ln_formula.png">
		<img src="lib/img/ln.png">
		<div>
			<p style="color: gray">ln(x + 1) vs its McLarin Series up to the 300th term (x from -6.6 to 6.6 with interval of 0.0044)</p>
		</div>
	</div>

	<div class="app-box">
		<form id="AppForm">
			<div class="form-group">
				<label>x:</label><input type="number" id="x" step="any" value="1" required>
			</div>
			<div class="form-group">
				<label>Number of Sequence:</label><input type="number" id="nseq" min="1" max="200" value="4" required>
			</div>
			<div class="form-group">
				<label>Precision:</label><input type="number" id="precision" min="1" value="6" required>
			</div>
			<input type="submit" name="" value="CALCULATE">
		</form>
		<button onclick="reset()">Reset</button>
		<br>
		<br>
		<div class="output">
			McLauren Sequence terms
			<div class="out border">
				
			</div>

			<button class="btn btn-primary" title="Add Additional Sequence" onclick="addSeq()">+</button><span>Terms: <span id="numterms">0</span></span>
			<form >
				<div class="form-group">
					<label>Output</label>
					<input type="number" id="progout" readonly>
				</div>
				<div class="form-group">
					<label>Actual Value</label>
					<input type="number" id="actual" readonly>
				</div>
				<div class="form-group">
					<label>Percent Error</label>
					<input type="number" id="pcnt" readonly>
				</div>
			</form>
		</div>
	</div>

</div>
<?php require('template/footer.php'); ?>
<script type="text/javascript">
	let active = 'content1';
	let output = 0;
	let actual = 0;
	let pcnt = 0;
	let curX = 0;
	let precision = 0;

	// active sequences
	let sequence = [];
	let curterm = 0;
	let fnc  = {
		// cos(x)
		content1:{
			sum: 0,
			actual: function(x){
				return Math.cos(x);
			},
			m: function(x, nseq){
				sum = 0;
				for ( n = 0; n < nseq; n++){
					let a = Math.pow(-1, n) * Math.pow(x, 2 * n);
					let b = factorial(2 * n);
					let term = a / b;
					sum = sum + term;
					sequence.push(term);
					curterm = curterm + 1;
				}
				return sum;
			},
			add: function(){
				let a = Math.pow(-1, curterm) * Math.pow(curX, 2 * curterm);
				let b = factorial(2 * curterm);
				let term = a/b;
				sequence.push(term);
				curterm += 1;
				return term;
			}
		},
		content2:{
			sum: 0,
			actual: function(x){
				return Math.sin(x);
			},
			m: function(x, nseq){
				sum = 0;
				
				for ( n = 0; n < nseq; n++){
					let a = Math.pow(-1, n) * Math.pow(x, 2 * n + 1);
					let b = factorial(2 * n + 1);
					let term = a / b;
					sum = sum + term;
					sequence.push(term);
					curterm = curterm + 1;
				}
				return sum;
			},
			add: function(){
				let a = Math.pow(-1, curterm) * Math.pow(curX, 2 * curterm + 1);
				let b = factorial(2 * curterm + 1);
				let term = a/b;
				sequence.push(term);
				curterm += 1;
				return term;
			}
		},
		content3:{
			sum: 0,
			actual: function(x){
				return Math.log(Number.parseFloat(x) + 1);

			},
			m: function(x, nseq){
				sum = 0;
				for ( n = 1; n <= nseq; n++){
					let a = Math.pow(-1, n-1) * Math.pow(x, n);
					let b = n;
					let term = a / b;
					sum = sum + term;
					sequence.push(term);
					curterm = curterm + 1;
				}
				return sum;
			},
			add: function(){

				let a = Math.pow(-1, curterm) * Math.pow(curX, curterm+1);
				let b = curterm+1;
				let term = a/b;
				sequence.push(term);
				curterm += 1;
				return term;
			}
		}
	}
	
	$(document).ready(function(){

	});
	function setActive(name){

	}

	$('#AppForm').submit(function(e){
		curterm = 0;
		e.preventDefault();
		sequence = [];
		let x = $('#x').val();
		curX = x;
		precision = $('#precision').val();
		let nseq = $('#nseq').val();

		let cur = fnc[active];
		cur.sum = 0;

		let fn = cur.m;
		output = fn(x, nseq);
		cur.sum = output;

		actual = cur.actual(x);
		
		setViews();
		// set content of sequences
		$q = $('.out');
		$q.html('');
		sequence.forEach(seq=>{
			$q.append(`<p>${seq}</p>`);
		})

		// focus
		$('html, body').animate({ scrollTop: $('.output').offset().top + 10 }, 'slow');
		
	})
	$("#selector").on('change', function(e){
		$('#'+active).toggle();
		active = $("#selector").val();
		$('#'+active).toggle();
		reset();
	})

	function addSeq(){
		if (curterm < 1)
			return;
		let cur = fnc[active];
		output = output + cur.add();
		cur.sum = output;
		setViews();
		$('.out').append(`<p>${sequence[sequence.length - 1]}</p>`);
		$('#nseq').val(Number.parseInt($('#nseq').val()) + 1);

	}

	function setViews(){
		pcnt = Math.abs(((output - actual) / actual) * 100);
		$('#actual').val(Number.parseFloat(actual).toFixed(precision));
		$('#progout').val(Number.parseFloat(output).toFixed(precision));
		$('#pcnt').val(Number.parseFloat(pcnt).toFixed(4));
		$('#numterms').html(curterm);
	}
	function reset(){
		sequence = [];
		output = 0;
		curterm = 0;
		$('form').trigger('reset');
		$('.out').html('');
		$('#numterms').html('0');
	}
</script>