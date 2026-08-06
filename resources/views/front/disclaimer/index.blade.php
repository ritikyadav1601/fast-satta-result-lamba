@extends('front.layout.master')

@section('content')
@include('front.components.fade-logo',['title' => $settings['website_name']])
<section>
    <div class="octoberresultchart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 style="text-transform: uppercase;">disclaimer</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="forChart termspage">
        <p>Welcome to the fast-satta-result.com website. Before using or accessing any information on this site, please read and understand the following disclaimer</p>
        <p>&nbsp;</p>
        <p><strong>Accuracy of Information</strong></p>
        <p>The information provided on this website is for general informational purposes only. While we strive to keep the information up to date and accurate, we make no representations or warranties of any kind, express or implied, about the completeness,
            accuracy, reliability, suitability, or availability concerning the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly
            at your own risk.</p>
        <p>&nbsp;</p>
        <p><strong>Not Legal Advice</strong></p>
        <p>This website does not provide any legal advice. The content is for informational purposes only and should not be considered as legal, financial, or professional advice. Users are encouraged to seek appropriate professional advice for their specific
            circumstances.</p>
        <p>&nbsp;</p>
        <p><strong>Third-Party Links</strong></p>
        <p>From our website, you can visit other websites through hyperlinks provided. While we strive to provide only quality links to useful and ethical websites, we have no control over the content and nature of these sites. The inclusion of any links
            does not necessarily imply a recommendation or endorse the views expressed within them.</p>
        <p><strong>Personal Responsibility</strong></p>
        <p>The use of the information on this website is entirely at your own risk. It is your responsibility to ensure that any products, services, or information available through this website meet your specific requirements. We shall not be liable for
            any loss or damage, including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from the use of this website.</p>
        <p><strong>Consent</strong></p>
        <p>By using this website, you hereby consent to our disclaimer and agree to its terms.</p>
        <p><strong>Updates and Changes</strong></p>
        <p>We reserve the right to make changes and to revise the above disclaimer at any time without notice. It is your responsibility to check periodically for any updates or changes. Continued use of the website following the posting of changes constitutes
            acceptance of those changes.</p>
        <p><strong>Contact Information</strong></p>
        <p>If you have any questions or concerns regarding this disclaimer, please feel free to contact us at mail@fast-satta-result.com.</p>
        <p><strong><em>Thank you for taking the time to read our disclaimer. We hope you find our website informative and useful.</em></strong></p>
        <p><br></p>
    </div>
</section>
@endsection