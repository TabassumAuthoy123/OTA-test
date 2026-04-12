<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class PolicyPagesSeeder extends Seeder
{
    public function run(): void
    {
        // ━━━ TERMS & CONDITIONS ━━━
        CmsPage::updateOrCreate(
            ['slug' => 'terms'],
            [
                'title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions - FaithTrip',
                'meta_description' => 'Read the terms and conditions for using FaithTrip flight booking services. Understand booking rules, cancellation policies, and passenger responsibilities.',
                'is_active' => true,
                'content' => <<<'HTML'
<h2>Introduction</h2>
<p>Welcome to FaithTrip ("we", "us", "our"). These Terms & Conditions govern your use of FaithTrip.net (the "Website") and all related services for domestic and international flight booking, ticketing, and travel arrangements. By accessing or using our services, you agree to be bound by these terms.</p>

<div class="policy-highlight">
<strong>Important:</strong> FaithTrip operates as a licensed Online Travel Agency (OTA) registered in Bangladesh. We act as an intermediary between you (the passenger) and the airline carriers. All flight tickets are subject to the respective airline's conditions of carriage.
</div>

<h2>Definitions</h2>
<ul>
<li><strong>"Booking"</strong> — A confirmed reservation for one or more passengers on a specific flight itinerary.</li>
<li><strong>"Traveler" / "Passenger"</strong> — Any person named in a booking for whom a ticket is issued.</li>
<li><strong>"Fare"</strong> — The total cost of air travel including base fare, taxes, surcharges, and service fees.</li>
<li><strong>"E-Ticket"</strong> — The electronic document that confirms your booking and serves as proof of purchase.</li>
<li><strong>"PNR"</strong> — Passenger Name Record — a unique alphanumeric code that identifies your booking in the airline's system.</li>
<li><strong>"GDS"</strong> — Global Distribution System — the computerized reservation system used to process bookings (e.g., Sabre, Amadeus).</li>
</ul>

<h2>Booking & Ticketing</h2>
<h3>2.1 Fare Accuracy</h3>
<p>All fares displayed on our website are sourced in real-time from GDS and airline systems. Prices are subject to change until the booking is confirmed and payment is processed. Displayed fares include base fare and applicable taxes unless stated otherwise.</p>

<h3>2.2 Booking Confirmation</h3>
<p>A booking is considered confirmed only when:</p>
<ol>
<li>Full payment has been received and processed successfully.</li>
<li>An E-Ticket with a valid PNR has been issued and sent to your registered email.</li>
<li>The booking status shows "Confirmed" in our system.</li>
</ol>

<div class="policy-warning">
<strong>Warning:</strong> A booking is NOT confirmed by simply submitting a reservation request. Until a valid E-Ticket is issued, the fare and availability are not guaranteed. Airlines may cancel unticketable bookings without notice.
</div>

<h3>2.3 Passenger Information</h3>
<p>You must provide accurate passenger details including:</p>
<ul>
<li>Full legal name as it appears on the travel document (passport/NID).</li>
<li>Date of birth and gender.</li>
<li>Valid contact number and email address.</li>
<li>Passport number and expiry date (for international flights).</li>
</ul>
<p>FaithTrip is not responsible for any issues arising from incorrect passenger information provided at the time of booking. Name changes after ticket issuance are subject to airline policies and may incur additional charges or may not be permitted.</p>

<h3>2.4 Baggage</h3>
<p>Baggage allowances are determined by the airline and fare class selected. Free baggage allowance varies by route and carrier. FaithTrip displays baggage information as provided by the airline but does not guarantee accuracy. Please verify directly with the airline before travel.</p>

<h2>Payment Terms</h2>
<h3>3.1 Accepted Payment Methods</h3>
<p>We accept the following payment methods:</p>
<ul>
<li><strong>Cards:</strong> Visa, Mastercard (Credit/Debit)</li>
<li><strong>Mobile Banking:</strong> bKash, Nagad, Rocket, Upay, Tap</li>
<li><strong>Bank Transfer:</strong> Direct bank deposit (BEFTN/NPSB)</li>
<li><strong>EMI:</strong> 0% EMI available on selected bank credit cards (3–12 month installments)</li>
</ul>

<h3>3.2 Currency</h3>
<p>All prices on FaithTrip are displayed in Bangladeshi Taka (BDT/৳). For international routes, the fare may be based on the airline's published fare in a foreign currency, converted to BDT at the prevailing exchange rate at the time of booking.</p>

<h3>3.3 Service Fee</h3>
<p>FaithTrip charges a convenience/service fee per booking. This fee is non-refundable and covers the cost of ticket issuance, GDS usage, and customer support. The fee is displayed before payment confirmation.</p>

<h2>Changes & Modifications</h2>
<h3>4.1 Date Change</h3>
<p>Date changes are subject to airline policy and fare rules. Most tickets allow date changes with:</p>
<ul>
<li>A date change penalty charged by the airline (varies by airline and fare class).</li>
<li>Fare difference, if the new date has a higher fare.</li>
<li>FaithTrip processing fee of BDT 500 per passenger per segment.</li>
</ul>

<h3>4.2 Route Change</h3>
<p>Route changes are generally not permitted for most fare types. If allowed by the airline, route changes may be treated as a cancellation and rebooking, subject to fare difference and penalties.</p>

<h3>4.3 Name Correction</h3>
<p>Minor spelling corrections (up to 3 characters) may be possible depending on the airline. Full name changes are not permitted after ticketing. Passengers are advised to carefully verify all details before confirming the booking.</p>

<h2>Cancellation & Refund</h2>
<p>Cancellation and refunds are governed by the airline's fare rules and our <a href="/page/refund">Refund Policy</a>. Key points:</p>
<ul>
<li>Cancellation must be requested through FaithTrip. Direct cancellation with the airline may not be processed through our system.</li>
<li>Refund amount depends on the fare type (refundable vs. non-refundable) and airline cancellation penalty.</li>
<li>FaithTrip service fees are non-refundable.</li>
<li>Refund processing time is 7–30 business days depending on the airline and payment method.</li>
</ul>

<h2>No-Show Policy</h2>
<div class="policy-warning">
<strong>Important:</strong> If a passenger fails to check in or board the flight ("No-Show"), the ticket value is forfeited. No refund will be issued for no-show bookings unless the airline fare rules explicitly allow partial recovery.
</div>
<p>For round-trip bookings, if you miss the outbound flight, the airline may automatically cancel your return flight. We strongly recommend contacting us immediately if you cannot travel on any segment.</p>

<h2>Airline Responsibility</h2>
<p>FaithTrip acts solely as an intermediary. We are not liable for:</p>
<ul>
<li>Flight delays, cancellations, or schedule changes made by the airline.</li>
<li>Overbooking or denied boarding by the airline.</li>
<li>Loss, damage, or delay of baggage.</li>
<li>In-flight services, meals, or seat assignments.</li>
<li>Airline operational issues, strikes, or force majeure events.</li>
</ul>
<p>In case of airline-initiated cancellations or schedule changes, we will assist you in rebooking or processing refunds as per the airline's policy.</p>

<h2>Visa & Travel Documents</h2>
<p>It is the passenger's sole responsibility to ensure they hold valid travel documents including:</p>
<ul>
<li>A valid passport with at least 6 months validity from the date of travel.</li>
<li>Required visas and transit permits for the destination and connecting countries.</li>
<li>Health certificates, vaccination records, or COVID-related documentation if required.</li>
</ul>
<p>FaithTrip is not responsible for denied boarding or immigration issues due to missing or invalid travel documents.</p>

<h2>Intellectual Property</h2>
<p>All content, design, logos, trademarks, and software on FaithTrip.net are the intellectual property of Softifybd and are protected under Bangladeshi and international copyright laws. Unauthorized use, reproduction, or distribution is strictly prohibited.</p>

<h2>Limitation of Liability</h2>
<p>To the maximum extent permitted by law, FaithTrip's total liability for any claim arising from or related to our services shall not exceed the total amount paid by you for the specific booking in question. We are not liable for any indirect, incidental, consequential, or punitive damages.</p>

<h2>Governing Law</h2>
<p>These Terms & Conditions are governed by the laws of the People's Republic of Bangladesh. Any disputes shall be subject to the exclusive jurisdiction of the courts in Dhaka, Bangladesh.</p>

<h2>Contact Us</h2>
<p>For any questions regarding these terms, please contact us:</p>
<ul>
<li><strong>Email:</strong> support@faithtrip.net</li>
<li><strong>Phone:</strong> +880 1XXX-XXXXXX</li>
<li><strong>Office:</strong> Dhaka, Bangladesh</li>
<li><strong>Business Hours:</strong> Saturday – Thursday, 9:00 AM – 10:00 PM (BST)</li>
</ul>
HTML
            ]
        );

        // ━━━ PRIVACY POLICY ━━━
        CmsPage::updateOrCreate(
            ['slug' => 'privacy'],
            [
                'title' => 'Privacy Policy',
                'meta_title' => 'Privacy Policy - FaithTrip',
                'meta_description' => 'Learn how FaithTrip collects, uses, and protects your personal data. Our privacy policy explains data handling for flight bookings and travel services.',
                'is_active' => true,
                'content' => <<<'HTML'
<h2>Introduction</h2>
<p>FaithTrip ("we", "us", "our") is committed to protecting your privacy and personal data. This Privacy Policy explains how we collect, use, store, and protect information when you use our website (FaithTrip.net) and related travel booking services.</p>

<div class="policy-highlight">
<strong>Our Commitment:</strong> We only collect data that is necessary to process your bookings and improve your travel experience. We never sell your personal information to third parties for marketing purposes.
</div>

<h2>Information We Collect</h2>
<h3>2.1 Personal Information (Provided by You)</h3>
<p>When you make a booking or create an account, we collect:</p>
<ul>
<li><strong>Identity Data:</strong> Full name, date of birth, gender, nationality.</li>
<li><strong>Contact Data:</strong> Email address, phone number, mailing address.</li>
<li><strong>Travel Documents:</strong> Passport number, passport expiry date, NID number (as required for ticketing).</li>
<li><strong>Payment Data:</strong> Card details (processed securely via payment gateway — we do NOT store full card numbers), mobile banking numbers, transaction IDs.</li>
<li><strong>Travel Preferences:</strong> Meal preferences, seat preferences, frequent flyer numbers, special assistance requests.</li>
</ul>

<h3>2.2 Automatically Collected Data</h3>
<p>When you visit our website, we automatically collect:</p>
<ul>
<li><strong>Device Data:</strong> IP address, browser type, operating system, device type.</li>
<li><strong>Usage Data:</strong> Pages visited, time spent, search queries, click patterns.</li>
<li><strong>Location Data:</strong> Approximate geographic location based on IP address.</li>
<li><strong>Cookie Data:</strong> Session cookies, preference cookies, and analytics cookies (see Cookie Policy section).</li>
</ul>

<h2>How We Use Your Data</h2>
<p>We use your personal information for the following purposes:</p>

<table>
<thead>
<tr><th>Purpose</th><th>Data Used</th><th>Legal Basis</th></tr>
</thead>
<tbody>
<tr><td>Process flight bookings & issue tickets</td><td>Identity, Contact, Travel Documents</td><td>Contract fulfillment</td></tr>
<tr><td>Process payments & refunds</td><td>Payment Data, Contact</td><td>Contract fulfillment</td></tr>
<tr><td>Send booking confirmations & itineraries</td><td>Contact, Identity</td><td>Contract fulfillment</td></tr>
<tr><td>Send schedule change or cancellation alerts</td><td>Contact</td><td>Legitimate interest</td></tr>
<tr><td>Customer support & dispute resolution</td><td>All relevant data</td><td>Legitimate interest</td></tr>
<tr><td>Improve website & services</td><td>Usage, Device, Cookie Data</td><td>Legitimate interest</td></tr>
<tr><td>Marketing & promotional offers</td><td>Contact (email)</td><td>Consent (opt-in)</td></tr>
<tr><td>Fraud prevention & security</td><td>Payment, Device, IP Data</td><td>Legitimate interest</td></tr>
<tr><td>Legal compliance & tax records</td><td>Identity, Payment, Booking Data</td><td>Legal obligation</td></tr>
</tbody>
</table>

<h2>Data Sharing</h2>
<p>We share your personal data only with the following categories of recipients, and only to the extent necessary:</p>

<h3>3.1 Airlines & GDS Providers</h3>
<p>We transmit passenger data (name, passport, contact) to airlines and GDS systems (Sabre, Amadeus, Galileo) to process bookings and issue tickets. This is essential for fulfilling your booking.</p>

<h3>3.2 Payment Processors</h3>
<p>Payment data is processed by PCI-DSS compliant payment gateways (SSL Commerz, bKash, Nagad). FaithTrip does not store full credit/debit card numbers on our servers.</p>

<h3>3.3 Government & Regulatory Bodies</h3>
<p>We may share passenger data with immigration authorities, customs, or security agencies as required by law — particularly for international travel (APIS/PNR data requirements).</p>

<h3>3.4 Service Partners</h3>
<p>We may share limited data with SMS/email service providers, cloud hosting providers, and analytics tools — all bound by data processing agreements.</p>

<div class="policy-warning">
<strong>We Never:</strong> Sell, rent, or trade your personal data to third-party marketers, data brokers, or advertisers. Your travel data and personal information are not commodities.
</div>

<h2>Data Security</h2>
<p>We implement industry-standard security measures to protect your data:</p>
<ul>
<li><strong>SSL/TLS Encryption:</strong> All data transmitted between your browser and our servers is encrypted using 256-bit SSL.</li>
<li><strong>Secure Storage:</strong> Personal data is stored on encrypted servers with access controls and regular security audits.</li>
<li><strong>PCI-DSS Compliance:</strong> Payment processing follows Payment Card Industry Data Security Standards.</li>
<li><strong>Access Controls:</strong> Only authorized personnel can access customer data, with activity logging and multi-factor authentication.</li>
<li><strong>Regular Audits:</strong> We conduct periodic security assessments and vulnerability testing.</li>
</ul>

<h2>Data Retention</h2>
<p>We retain your personal data for the following periods:</p>

<table>
<thead>
<tr><th>Data Type</th><th>Retention Period</th><th>Reason</th></tr>
</thead>
<tbody>
<tr><td>Booking & ticket records</td><td>7 years</td><td>Legal & tax compliance</td></tr>
<tr><td>Payment records</td><td>7 years</td><td>Financial regulations</td></tr>
<tr><td>Account information</td><td>Until account deletion</td><td>Service provision</td></tr>
<tr><td>Customer support records</td><td>3 years</td><td>Dispute resolution</td></tr>
<tr><td>Website analytics</td><td>24 months</td><td>Service improvement</td></tr>
<tr><td>Marketing preferences</td><td>Until consent withdrawn</td><td>Consent-based</td></tr>
</tbody>
</table>

<h2>Your Rights</h2>
<p>You have the following rights regarding your personal data:</p>
<ul>
<li><strong>Right of Access:</strong> Request a copy of the personal data we hold about you.</li>
<li><strong>Right of Correction:</strong> Request correction of inaccurate or incomplete data.</li>
<li><strong>Right of Deletion:</strong> Request deletion of your account and personal data (subject to legal retention requirements).</li>
<li><strong>Right to Opt-Out:</strong> Unsubscribe from marketing emails at any time using the "Unsubscribe" link in our emails.</li>
<li><strong>Right to Data Portability:</strong> Request your booking history in a machine-readable format.</li>
</ul>
<p>To exercise these rights, email us at <strong>privacy@faithtrip.net</strong> with your registered email and a description of your request. We will respond within 30 business days.</p>

<h2>Cookies</h2>
<p>Our website uses cookies to enhance your experience. Types of cookies we use:</p>
<ul>
<li><strong>Essential Cookies:</strong> Required for website functionality (session management, security). Cannot be disabled.</li>
<li><strong>Preference Cookies:</strong> Remember your settings (language, currency, recent searches).</li>
<li><strong>Analytics Cookies:</strong> Help us understand how visitors use our website (Google Analytics). Anonymized data only.</li>
<li><strong>Marketing Cookies:</strong> Used for targeted advertising (only with your explicit consent).</li>
</ul>
<p>You can manage cookie preferences through your browser settings. Disabling essential cookies may affect website functionality.</p>

<h2>Children's Privacy</h2>
<p>Our services are not directed at children under 18 years of age. While minors may be included as passengers in bookings made by adults, we do not knowingly collect personal information from children independently. A parent or legal guardian must make all bookings involving minors.</p>

<h2>International Data Transfers</h2>
<p>When you book international flights, your data may be transferred to airlines and airport authorities in other countries. We ensure that such transfers are conducted in compliance with applicable data protection laws and that recipients maintain adequate security measures.</p>

<h2>Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Significant changes will be communicated via email or a prominent notice on our website. The "Last Updated" date at the top of this page indicates when the policy was last revised.</p>

<h2>Contact Us</h2>
<p>For privacy-related questions or to exercise your data rights:</p>
<ul>
<li><strong>Data Protection Contact:</strong> privacy@faithtrip.net</li>
<li><strong>General Support:</strong> support@faithtrip.net</li>
<li><strong>Phone:</strong> +880 1XXX-XXXXXX</li>
<li><strong>Office:</strong> Dhaka, Bangladesh</li>
</ul>
HTML
            ]
        );

        // ━━━ REFUND POLICY ━━━
        CmsPage::updateOrCreate(
            ['slug' => 'refund'],
            [
                'title' => 'Refund Policy',
                'meta_title' => 'Refund Policy - FaithTrip',
                'meta_description' => 'Understand FaithTrip\'s refund policy for flight cancellations, schedule changes, and unused tickets. Learn about refund timelines and eligibility.',
                'is_active' => true,
                'content' => <<<'HTML'
<h2>Overview</h2>
<p>This Refund Policy outlines the terms under which FaithTrip processes refunds for flight bookings. As an Online Travel Agency (OTA), refund eligibility and amounts are primarily determined by the airline's fare rules and conditions of carriage. FaithTrip facilitates the refund process between you and the airline.</p>

<div class="policy-highlight">
<strong>Key Principle:</strong> Whether a ticket is refundable or non-refundable depends on the fare type you purchased. This is determined by the airline at the time of booking, and the refund rules are displayed before you confirm payment.
</div>

<h2>Refund Eligibility</h2>
<h3>Refundable Tickets</h3>
<p>If you purchased a refundable fare class, you are entitled to a refund minus:</p>
<ul>
<li>Airline cancellation penalty (varies by airline and route).</li>
<li>FaithTrip service/processing fee (non-refundable, BDT 500–1000 per passenger).</li>
<li>Payment gateway charges (if applicable).</li>
</ul>

<h3>Non-Refundable Tickets</h3>
<p>Non-refundable tickets generally cannot be refunded. However, the following exceptions may apply:</p>
<ul>
<li><strong>Airline-initiated cancellation:</strong> If the airline cancels the flight, you are entitled to a full refund regardless of fare type.</li>
<li><strong>Schedule change (> 2 hours):</strong> Major schedule changes may qualify for a full refund.</li>
<li><strong>Medical emergency:</strong> Some airlines may offer compassionate refunds or future travel credit with valid medical documentation.</li>
<li><strong>Visa rejection:</strong> Select airlines may process refunds upon submission of official visa rejection letter (subject to airline policy).</li>
<li><strong>Death of passenger:</strong> Full refund may be processed with death certificate and valid documentation.</li>
</ul>

<h2>Refund Amount Breakdown</h2>
<p>The refund amount is calculated as follows:</p>

<table>
<thead>
<tr><th>Component</th><th>Refundable?</th><th>Notes</th></tr>
</thead>
<tbody>
<tr><td>Base Fare</td><td>Yes (if refundable ticket)</td><td>Minus airline cancellation penalty</td></tr>
<tr><td>Airport Taxes (YQ/YR)</td><td>Partially</td><td>Airline fuel surcharges may not be refundable</td></tr>
<tr><td>Government Taxes (BD/UT)</td><td>Yes</td><td>Usually fully refundable on unused tickets</td></tr>
<tr><td>FaithTrip Service Fee</td><td>No</td><td>Non-refundable once ticket is issued</td></tr>
<tr><td>Payment Gateway Fee</td><td>No</td><td>Transaction charges are non-refundable</td></tr>
<tr><td>EMI Processing Fee</td><td>No</td><td>EMI interest/charges are non-refundable</td></tr>
</tbody>
</table>

<h2>Refund Timeline</h2>
<p>Refund processing times depend on the airline and your payment method:</p>

<table>
<thead>
<tr><th>Scenario</th><th>Processing Time</th></tr>
</thead>
<tbody>
<tr><td>Airline-initiated cancellation</td><td>7–15 business days</td></tr>
<tr><td>Voluntary cancellation (refundable)</td><td>15–30 business days</td></tr>
<tr><td>Credit/Debit card refund</td><td>7–14 business days (after airline processes)</td></tr>
<tr><td>bKash / Nagad / Mobile Banking</td><td>3–7 business days (after airline processes)</td></tr>
<tr><td>Bank transfer refund</td><td>7–14 business days (after airline processes)</td></tr>
<tr><td>EMI cancellation refund</td><td>30–45 business days (processed through bank)</td></tr>
</tbody>
</table>

<div class="policy-highlight">
<strong>Note:</strong> The refund timeline starts from the date the airline approves and processes the refund, not from the date of your cancellation request. Some airlines may take 4–8 weeks to process refunds during peak periods.
</div>

<h2>How to Request a Refund</h2>
<p>Follow these steps to request a refund:</p>
<ol>
<li><strong>Contact Us:</strong> Call our support at <strong>+880 1XXX-XXXXXX</strong> or email <strong>refund@faithtrip.net</strong> with your booking reference (PNR) and reason for cancellation.</li>
<li><strong>Verification:</strong> Our team will verify your booking details and check the airline's refund rules for your fare class.</li>
<li><strong>Cancellation:</strong> Upon your confirmation, we will initiate the cancellation with the airline.</li>
<li><strong>Refund Processing:</strong> The refund will be processed to your original payment method once approved by the airline.</li>
<li><strong>Confirmation:</strong> You will receive an email confirmation with the refund amount and expected timeline.</li>
</ol>

<div class="policy-warning">
<strong>Important:</strong> Do not contact the airline directly for refunds on tickets purchased through FaithTrip. Direct airline refund requests may cause conflicts and delay the process. Always process refunds through FaithTrip.
</div>

<h2>Cancellation Charges</h2>
<h3>Domestic Flights (Bangladesh)</h3>
<table>
<thead>
<tr><th>Cancellation Time</th><th>Typical Airline Penalty</th></tr>
</thead>
<tbody>
<tr><td>More than 24 hours before departure</td><td>BDT 500–1,500 per passenger</td></tr>
<tr><td>12–24 hours before departure</td><td>BDT 1,000–2,500 per passenger</td></tr>
<tr><td>Less than 12 hours before departure</td><td>BDT 2,000–3,500 or non-refundable</td></tr>
<tr><td>After departure (No-Show)</td><td>Non-refundable (most airlines)</td></tr>
</tbody>
</table>

<h3>International Flights</h3>
<table>
<thead>
<tr><th>Cancellation Time</th><th>Typical Airline Penalty</th></tr>
</thead>
<tbody>
<tr><td>More than 72 hours before departure</td><td>USD 50–150 per passenger</td></tr>
<tr><td>24–72 hours before departure</td><td>USD 75–250 per passenger</td></tr>
<tr><td>Less than 24 hours before departure</td><td>USD 100–350 or non-refundable</td></tr>
<tr><td>After departure (No-Show)</td><td>Non-refundable (most airlines)</td></tr>
</tbody>
</table>

<p><em>Note: Actual penalties vary by airline, route, and fare class. The amounts above are indicative ranges. Actual charges will be confirmed at the time of cancellation request.</em></p>

<h2>Special Refund Scenarios</h2>

<h3>6.1 Airline-Initiated Cancellation</h3>
<p>If the airline cancels your flight, you are entitled to:</p>
<ul>
<li><strong>Full refund</strong> of the ticket price (including taxes and FaithTrip service fee).</li>
<li><strong>Free rebooking</strong> on the next available flight (subject to availability).</li>
<li>FaithTrip will proactively notify you of cancellations and your available options.</li>
</ul>

<h3>6.2 Schedule Change</h3>
<p>If the airline changes the flight schedule by more than 2 hours:</p>
<ul>
<li>You may accept the new schedule at no extra cost.</li>
<li>You may request a full refund.</li>
<li>You may rebook to a different date/time without penalty.</li>
</ul>

<h3>6.3 Duplicate Booking</h3>
<p>If a duplicate booking is created due to a system error, the duplicate ticket will be cancelled and fully refunded. If you made a duplicate booking intentionally, standard cancellation charges apply.</p>

<h3>6.4 Partial Use (Round-Trip)</h3>
<p>If you used the outbound segment but wish to cancel the return:</p>
<ul>
<li>The refund is calculated based on the return segment's fare value minus applicable penalties.</li>
<li>If the ticket was purchased at a round-trip discount, the refund may be recalculated at the one-way fare difference.</li>
</ul>

<h2>Non-Refundable Items</h2>
<p>The following are non-refundable under any circumstances:</p>
<ul>
<li>FaithTrip convenience/service fee (once ticket is issued).</li>
<li>Payment gateway charges.</li>
<li>Travel insurance premium (if purchased).</li>
<li>Airport meet & greet services (if availed).</li>
<li>SMS notification charges.</li>
</ul>

<h2>Dispute Resolution</h2>
<p>If you disagree with a refund amount or decision:</p>
<ol>
<li>Email <strong>refund@faithtrip.net</strong> with your PNR, booking details, and a description of the dispute.</li>
<li>Our team will review the case and respond within 3–5 business days.</li>
<li>If the dispute involves airline policy, we will escalate to the airline and share their response with you.</li>
<li>If unresolved, you may file a complaint with the Civil Aviation Authority of Bangladesh (CAAB).</li>
</ol>

<h2>Force Majeure</h2>
<p>In cases of force majeure events including natural disasters, pandemics, government travel restrictions, political unrest, or airline strikes, refund policies may be modified. FaithTrip will communicate any special refund provisions and work with airlines to secure the best possible outcome for affected passengers.</p>

<h2>Contact for Refunds</h2>
<p>For refund requests and inquiries:</p>
<ul>
<li><strong>Refund Desk:</strong> refund@faithtrip.net</li>
<li><strong>General Support:</strong> support@faithtrip.net</li>
<li><strong>Phone:</strong> +880 1XXX-XXXXXX</li>
<li><strong>WhatsApp:</strong> +880 1XXX-XXXXXX</li>
<li><strong>Business Hours:</strong> Saturday – Thursday, 9:00 AM – 10:00 PM (BST)</li>
</ul>
HTML
            ]
        );
    }
}
