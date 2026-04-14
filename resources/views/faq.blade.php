<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>FAQ - QuickFun Games</title>
		<style>
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}

			body {
				font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				min-height: 100vh;
				padding: 20px;
				line-height: 1.6;
			}

			.container {
				max-width: 900px;
				margin: 0 auto;
				background: white;
				border-radius: 15px;
				box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
				overflow: hidden;
			}

			.header {
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				color: white;
				padding: 40px 30px;
				text-align: center;
			}

			.header h1 {
				font-size: 2.5em;
				margin-bottom: 10px;
				font-weight: 700;
			}

			.header p {
				font-size: 1em;
				opacity: 0.9;
			}

			.content {
				padding: 40px 30px;
				color: #333;
			}

			.content h2 {
				color: #667eea;
				margin-top: 30px;
				margin-bottom: 15px;
				font-size: 1.8em;
				border-bottom: 2px solid #667eea;
				padding-bottom: 10px;
			}

			.content p {
				margin-bottom: 15px;
				color: #555;
				text-align: justify;
			}

			.faq-item {
				margin-bottom: 30px;
			}

			.faq-question {
				font-weight: 700;
				font-size: 1.2em;
				color: #667eea;
				margin-bottom: 10px;
			}

			.faq-answer {
				color: #555;
				margin-bottom: 15px;
			}

			.unsubscribe-link {
				display: inline-block;
				margin: 10px 0;
				padding: 10px 20px;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				color: white;
				text-decoration: none;
				border-radius: 5px;
				font-weight: 600;
				transition: transform 0.2s;
			}

			.unsubscribe-link:hover {
				transform: scale(1.05);
			}

			.email-link {
				color: #667eea;
				text-decoration: underline;
			}

			.email-link:hover {
				color: #764ba2;
			}

			@media (max-width: 768px) {
				body {
					padding: 10px;
				}

				.header h1 {
					font-size: 1.8em;
				}

				.content {
					padding: 25px 20px;
				}

				.content h2 {
					font-size: 1.5em;
				}
			}

			@media (max-width: 480px) {
				.header {
					padding: 30px 20px;
				}

				.header h1 {
					font-size: 1.5em;
				}

				.content {
					padding: 20px 15px;
				}
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="header">
				<h1>QuickFun FAQ</h1>
				<p>Frequently Asked Questions</p>
			</div>

			<div class="content">
				<div class="faq-item">
					<div class="faq-question">What is QuickFun?</div>
					<div class="faq-answer">
						QuickFun is a place where you can find great games that have been carefully picked by other people playing games. No install needed, just play them in your browser.
					</div>
				</div>

				<div class="faq-item">
					<div class="faq-question">Is QuickFun free?</div>
					<div class="faq-answer">
						QuickFun is free of ads and In-application purchases so you can focus on discovering and playing great and fun games. To play QuickFun games you need to subscribe, cost depending on your operator and country.
					</div>
				</div>

{{--				<div class="faq-item">--}}
{{--					<div class="faq-question">How can I unsubscribe from QuickFun?</div>--}}
{{--					<div class="faq-answer">--}}
{{--						<div style="margin: 10px 0;">--}}
{{--							<a href="javascript:void(0)" class="unsubscribe-link">Click here to unsubscribe</a>--}}
{{--						</div>--}}
{{--						<p>--}}
{{--							Alternatively, you can unsubscribe by sending STOP to your operator's shortcode or through your operator's customer care portal. For assistance please send an email to--}}
{{--							<a href="mailto:info@mediaworldiq.com?subject=QuickFun Support [IQ] - Unsubscribe Request" class="email-link">info@mediaworldiq.com</a>.--}}
{{--						</p>--}}
{{--					</div>--}}
{{--				</div>--}}

				<div class="faq-item">
					<div class="faq-question">It's not working :-(</div>
					<div class="faq-answer">
						<p>
							Sorry to hear that, write us on
							<a href="mailto:info@mediaworldiq.com?subject=QuickFun Support [IQ] - Technical Issue" class="email-link">info@mediaworldiq.com</a>.
							We like to understand what phone you are using, what browser (if you know) and what country and operator you have + that you describe what the issue is. The more info you provide, the better we can help you!
						</p>
					</div>
				</div>
			</div>
		</div>

		<script>
			function handleUnsubscribe() {
				// Get MSISDN from URL parameters or prompt user
				const urlParams = new URLSearchParams(window.location.search);
				let msisdn = urlParams.get('msisdn');

				if (!msisdn) {
					msisdn = prompt('Please enter your phone number (without country code):');
					if (!msisdn) {
						return;
					}
					// Normalize MSISDN
					msisdn = msisdn.replace(/[^0-9]/g, '');
					if (!msisdn.startsWith('964')) {
						msisdn = '964' + msisdn;
					}
				}

				if (confirm('Are you sure you want to unsubscribe from QuickFun?')) {
					// Call unsubscribe API
					fetch('/api/dcb/unsubscribe', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'Accept': 'application/json'
						},
						body: JSON.stringify({
							msisdn: msisdn,
							service: 'dcb_mediaworld' // Default service, can be made dynamic
						})
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							alert('You have been successfully unsubscribed from QuickFun.');
						} else {
							alert('Unsubscribe failed: ' + (data.message || 'Please contact support at info@mediaworldiq.com'));
						}
					})
					.catch(error => {
						console.error('Error:', error);
						alert('An error occurred. Please contact support at info@mediaworldiq.com');
					});
				}
			}
		</script>
	</body>
</html>

