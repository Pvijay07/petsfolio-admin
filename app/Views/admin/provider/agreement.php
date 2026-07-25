<html>

<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 8px;
      padding: 40px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #3498db;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }

    h4 {
      color: #2c3e50;
      border-bottom: 1px solid #bdc3c7;
      padding-bottom: 5px;
      margin-top: 30px;
      margin-bottom: 15px;
      font-size: 14px;
      text-transform: uppercase;
    }

    ul {
      margin-top: 5px;
      margin-bottom: 15px;
      padding-left: 20px;
    }

    li {
      margin-bottom: 8px;
      text-align: justify;
    }

    .header h2 {
      color: #2c3e50;
      margin: 0 0 10px 0;
    }

    .header h3 {
      color: #34495e;
      margin: 0;
      font-size: 18px;
      letter-spacing: 1px;
    }

    .content {
      color: #333;
      font-size: 13px;
      line-height: 1.6;
    }

    p {
      text-align: justify;
      margin-bottom: 12px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      background: #f9f9f9;
      border-radius: 8px;
    }

    table.grid {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      margin-top: 20px;
    }

    table.grid td {
      padding: 15px;
      vertical-align: top;
      width: 50%;
      border: 1px solid #ddd;
      background: #fafafa;
    }

    table.signature-section {
      background: transparent;
      margin-top: 40px;
      page-break-inside: avoid;
    }

    table.signature-section td {
      vertical-align: top;
      width: 45%;
    }

    .sig-box {
      border-top: 1px solid #333;
      padding-top: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Petsfolio</h2>
      <h3><?= strtoupper($serviceName) ?> PARTNER AGREEMENT</h3>
      <p>Independent Contractor Agreement for Pet Service Providers</p>
    </div>
    <div class="content">
      <p><strong>IMPORTANT:</strong> Please read this Agreement carefully before registering on the Platform. By clicking "I Agree" during the registration process, you confirm that you have read, understood, and agreed to be bound by all terms and conditions of this Agreement. This Agreement is legally binding under the Indian Contract Act, 1872 and the Information Technology Act, 2000.</p>

      <table class="grid">
        <tr>
          <td>
            <strong style="color:#2c3e50; font-size:14px;">PLATFORM (Petsfolio)</strong><br><br>
            <strong>Petsfolio Private Limited</strong><br>
            CIN: <br>
            Registered Office: 3rd floor, 3-6-327, Basheer Bagh,<br>
            Hyderabad, Telangana 500001<br>
            Email: info.petsfolio@gmail.com
          </td>
          <td>
            <strong style="color:#2c3e50; font-size:14px;">SERVICE PROVIDER (You)</strong><br><br>
            <strong>Full Name:</strong> <?= $provider->name ?><br>
            <strong>Aadhaar No.:</strong> <?= $aadhaar_no ?><br>
            <strong>Mobile:</strong> <?= $provider->phone ?><br>
            <strong>Service Type:</strong> <?= $serviceName ?>
          </td>
        </tr>
      </table>

      <h4>SECTION 1 — DEFINITIONS</h4>
      <p>The following terms shall have the meanings ascribed to them below throughout this Agreement:</p>
      <ul>
        <li><strong>"Platform"</strong>: The mobile application, website, and all related technology operated by Petsfolio Pvt. Ltd. for connecting pet owners with service providers.</li>
        <li><strong>"Service Provider" / "You"</strong>: The individual who registers on the Platform to offer pet care services including but not limited to grooming, walking, training, veterinary consultation, and pet sitting.</li>
        <li><strong>"Client" / "Platform Customer"</strong>: Any pet owner or individual who discovers, contacts, or books a Service Provider through the Platform.</li>
        <li><strong>"Services"</strong>: The pet care services offered by the Service Provider through the Platform as selected during registration.</li>
        <li><strong>"Booking"</strong>: A confirmed service request made by a Client to a Service Provider through the Platform.</li>
        <li><strong>"Commission"</strong>: The percentage of each Booking amount retained by the Platform as consideration for providing access to the Platform and its services.</li>
        <li><strong>"PawWallet" / "Wallet"</strong>: The in-app digital wallet through which earnings are credited to the Service Provider after service completion.</li>
        <li><strong>"Confidential Information"</strong>: All Client data, Platform data, pricing, business processes, software, and any non-public information shared by the Platform.</li>
      </ul>

      <h4>SECTION 2 — NATURE OF RELATIONSHIP</h4>
      <strong>2.1. Independent Contractor Status</strong>
      <p>The Service Provider is engaged by the Platform strictly as an independent contractor. Nothing in this Agreement shall be construed to create an employer-employee relationship, partnership, joint venture, agency, or franchise between the Platform and the Service Provider.</p>
      <p>The Service Provider expressly acknowledges and agrees to the following:</p>
      <ul>
        <li>The Service Provider shall have complete discretion over their working hours, the specific Clients they accept, and the manner in which they provide Services, subject only to the Platform\'s quality standards and this Agreement.</li>
        <li>The Platform does not guarantee a minimum number of Bookings, minimum earnings, or continuous availability of work on the Platform.</li>
        <li>The Service Provider is free to engage in other occupations, businesses, or activities, provided such activities do not constitute a breach of the Non-Compete obligations set out in Section 7 of this Agreement.</li>
        <li>The Service Provider shall provide their own tools, equipment, and supplies necessary to perform the Services (grooming kits, leashes, transport, etc.) unless otherwise agreed in writing.</li>
        <li>The Platform shall not supervise, direct, or control the day-to-day manner in which the Service Provider performs Services. The Platform only sets minimum quality and safety standards.</li>
      </ul>

      <strong>2.1.1 No Employment Benefits</strong>
      <p>As an independent contractor, the Service Provider is not entitled to and shall not receive any employment benefits from the Platform, including but not limited to:</p>
      <ul>
        <li>Provident Fund (PF) contributions under the Employees\' Provident Funds and Miscellaneous Provisions Act, 1952</li>
        <li>Employee State Insurance (ESI) under the ESI Act, 1948</li>
        <li>Gratuity under the Payment of Gratuity Act, 1972</li>
        <li>Paid leave, sick leave, or any other statutory leave benefits</li>
        <li>Medical insurance or health benefits</li>
        <li>Notice pay or severance compensation upon termination of this Agreement</li>
      </ul>

      <strong>2.1.2 Tax Obligations</strong>
      <p>The Service Provider acknowledges sole responsibility for the following tax and statutory obligations:</p>
      <ul>
        <li>Payment of all applicable income tax on earnings received through the Platform, including advance tax if applicable</li>
        <li>GST registration and compliance if the Service Provider\'s annual earnings exceed the applicable threshold under the GST Act</li>
        <li>Filing of income tax returns and maintaining proper books of accounts as required under applicable law</li>
      </ul>
      <p>The Platform shall deduct TDS (Tax Deducted at Source) at applicable rates under Section 194C or other relevant provisions of the Income Tax Act, 1961 on payments made to the Service Provider. A Form 16A / TDS certificate shall be issued as required by law.</p>

      <h4>SECTION 3 — CUSTOMER OWNERSHIP & OFF-PLATFORM PROHIBITION</h4>
      <strong>3.1. Platform Customer Ownership</strong>
      <p>The Service Provider acknowledges and agrees that all Clients who discover, contact, or engage the Service Provider through the Platform are Platform Customers. The relationship between such Clients and the Service Provider exists solely by virtue of the Platform\'s facilitation.</p>
      <p>The Service Provider shall not, directly or indirectly:</p>
      <ul>
        <li>Solicit, accept, or perform any Services for a Platform Customer through any channel other than the Platform, including but not limited to WhatsApp, phone calls, email, social media, or in-person arrangement, for a period of twelve (12) months from the date of their first Booking on the Platform.</li>
        <li>Share personal contact details (phone number, personal WhatsApp, social media handles, home address, or any other personal information) with a Platform Customer for the purpose of conducting business outside the Platform.</li>
        <li>Encourage, suggest, or facilitate any Platform Customer to contact them outside the Platform for Services.</li>
        <li>Accept cash, bank transfer, UPI, or any other payment directly from a Platform Customer for Services rendered through or discovered via the Platform.</li>
      </ul>
      <p><em>Plain language explanation: If a pet owner finds you through our app, they are our mutual customer. You cannot take them off the app and serve them privately. Any booking from a client who found you on our platform must go through our platform — for at least 12 months from when you first met them through us.</em></p>

      <strong>3.2 Consequences of Off-Platform Transactions</strong>
      <p>Any breach of Section 3.1 shall entitle the Platform to:</p>
      <ul>
        <li>Immediately suspend or permanently remove the Service Provider from the Platform without notice or compensation</li>
        <li>Recover from the Service Provider an amount equivalent to the Platform\'s estimated commission loss on all off-platform transactions conducted in breach of this clause</li>
        <li>Pursue legal remedies including injunctive relief and damages under applicable law</li>
      </ul>

      <h4>SECTION 4 — COMMISSION & PAYMENT TERMS</h4>
      <strong>4.1. Commission Structure</strong>
      <p>In consideration for access to the Platform, Client base, technology infrastructure, marketing, payment processing, and dispute resolution services, the Service Provider agrees to pay the Platform a commission on each completed Booking as follows:</p>
      <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr style="background-color: #f2f2f2;">
          <th>Service Type</th>
          <th>Commission Rate</th>
          <th>Payment Release</th>
        </tr>
        <tr>
          <td>Grooming</td>
          <td>10% of booking value</td>
          <td>Within 2 hours of completion</td>
        </tr>
        <tr>
          <td>Dog Walking</td>
          <td>10% of booking value</td>
          <td>Within 2 hours of task completion</td>
        </tr>
        <tr>
          <td>Training</td>
          <td>10% of booking value</td>
          <td>Within 2 hours of completion</td>
        </tr>
        <tr>
          <td>Veterinary Consultation</td>
          <td>10% of booking value</td>
          <td>Within 2 hours of completion</td>
        </tr>
        <tr>
          <td>Pet Sitting</td>
          <td>10% of booking value</td>
          <td>Upon task completion confirmation</td>
        </tr>
      </table>
      <p>Commission rates are subject to change with 30 days\' prior written notice to the Service Provider. Continued use of the Platform after such notice shall constitute acceptance of the revised commission rates.</p>

      <strong>4.2 Payment Release Mechanism</strong>
      <p>The Platform operates a task-based payment release system. Earnings are credited to the Service Provider\'s PawWallet only upon:</p>
      <ul>
        <li>The Client confirming completion of the Service through the app, OR</li>
        <li>Automatic system release after 24 hours if the Client does not raise a dispute, OR</li>
        <li>Manual release by the Platform\'s customer care team upon resolution of any dispute</li>
      </ul>
      <p>The Platform shall not be liable for any delay in payment caused by system outages, payment gateway failures, or force majeure events beyond its reasonable control.</p>

      <h4>SECTION 5 — LIABILITY & INDEMNIFICATION</h4>
      <strong>5.1. Service Provider\'s Liability</strong>
      <p>The Service Provider shall be solely and fully liable for:</p>
      <ul>
        <li>Any injury, illness, harm, or death caused to a pet during the provision of Services</li>
        <li>Any damage to a Client\'s property (home, vehicle, belongings) occurring during the provision of Services</li>
        <li>Any personal injury caused to a Client, third party, or themselves during the provision of Services</li>
        <li>Any breach of applicable law, including animal welfare laws, during the provision of Services</li>
        <li>Any misrepresentation made to Clients regarding qualifications, experience, or capabilities</li>
      </ul>

      <strong>5.2 Platform as Marketplace — Limitation of Liability</strong>
      <p>Petsfolio is a technology marketplace that connects pet owners with independent service providers. The Platform does not itself provide pet care services and is not responsible for:</p>
      <ul>
        <li>The quality, safety, or outcome of any Services provided by the Service Provider</li>
        <li>Any act, omission, negligence, or misconduct of the Service Provider</li>
        <li>Any dispute between the Service Provider and a Client arising from the provision of Services</li>
      </ul>
      <p>The Platform\'s maximum liability to the Service Provider under this Agreement shall not exceed the total commission earned by the Platform from the Service Provider\'s bookings in the preceding three (3) months.</p>

      <strong>5.3 Indemnification</strong>
      <p>The Service Provider agrees to indemnify, defend, and hold harmless Petsfolio Pvt. Ltd., its directors, officers, employees, and agents from and against any and all claims, damages, losses, costs, and expenses (including reasonable legal fees) arising out of or relating to: (a) the Services provided by the Service Provider; (b) any breach of this Agreement; (c) any negligent or wrongful act or omission of the Service Provider; or (d) any violation of applicable law.</p>

      <h4>SECTION 6 — NON-COMPETE & NON-SOLICITATION</h4>
      <strong>6.1. Non-Compete Obligation</strong>
      <p>During the term of this Agreement and for a period of six (6) months following termination or expiry of this Agreement for any reason, the Service Provider shall not, directly or indirectly:</p>
      <ul>
        <li>Register as a service provider on any competing pet services aggregator platform operating in the same city/cities where they provided Services through the Platform</li>
        <li>Establish, operate, or participate in any business that directly competes with the Platform\'s core pet services marketplace in the areas where they were active</li>
      </ul>

      <strong>6.2 Non-Solicitation of Clients</strong>
      <p>For a period of twelve (12) months following the date of last active Booking, the Service Provider shall not solicit, approach, or accept business from any Platform Customer — whether or not such Customer initiated contact — for services similar to those offered on the Platform, through any channel other than the Platform.</p>

      <h4>SECTION 7 — TERMINATION & REMOVAL</h4>
      <strong>7.1. Termination by Platform — With Cause</strong>
      <p>The Platform may immediately suspend or permanently terminate the Service Provider\'s access to the Platform, without notice or compensation, upon the occurrence of any of the following:</p>
      <ul>
        <li>Breach of any provision of this Agreement, including off-platform solicitation of Clients</li>
        <li>Receiving a Client rating below 3 stars sustained over 3 consecutive bookings</li>
        <li>Receipt of verified complaints of abuse, harassment, misconduct, or criminal behaviour</li>
        <li>Providing false information, fake qualifications, or fraudulent documentation during registration</li>
        <li>Any act causing harm to a pet, Client, or third party</li>
        <li>Non-compliance with GPS tracking, task confirmation, or other Platform operational requirements</li>
      </ul>

      <strong>7.2 Termination by Platform — Without Cause</strong>
      <p>The Platform may terminate this Agreement without cause by providing thirty (30) days\' written notice to the Service Provider. The Platform shall release all pending wallet earnings within seven (7) business days of the effective termination date.</p>

      <strong>7.3 Termination by Service Provider</strong>
      <p>The Service Provider may terminate this Agreement at any time by providing fifteen (15) days\' written notice to the Platform. During the notice period, the Service Provider must fulfil all confirmed Bookings. Failure to do so constitutes a breach of this Agreement.</p>

      <h4>SECTION 8 — GENERAL PROVISIONS</h4>
      <strong>8.1. Governing Law & Jurisdiction</strong>
      <p>This Agreement shall be governed by and construed in accordance with the laws of India. Any dispute arising under or in connection with this Agreement shall be subject to the exclusive jurisdiction of the courts of Hyderabad, Telangana.</p>

      <strong>8.2. Dispute Resolution</strong>
      <p>In the event of any dispute, the Parties shall first attempt to resolve the matter through good-faith negotiation within fifteen (15) days of written notice of the dispute. If unresolved, the matter shall be referred to arbitration under the Arbitration and Conciliation Act, 1996, with a sole arbitrator appointed by mutual agreement.</p>

      <strong>8.3. Entire Agreement</strong>
      <p>This Agreement constitutes the entire agreement between the Parties with respect to its subject matter and supersedes all prior discussions, understandings, or agreements. Any modification must be in writing and agreed to by both Parties.</p>

      <strong>8.4. Severability</strong>
      <p>If any provision of this Agreement is held to be invalid or unenforceable, the remaining provisions shall continue in full force and effect.</p>

      <strong>8.5. Digital Acceptance</strong>
      <p>The Service Provider\'s acceptance of this Agreement via the in-app checkbox and "Agree & Activate" button shall constitute a valid, legally binding electronic signature under the Information Technology Act, 2000. The Platform shall maintain a timestamped record of such acceptance, linked to the Service Provider\'s Aadhaar-verified account, as proof of consent.</p>

      <table class="signature-section" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
          <td style="width: 45%; vertical-align: top;">
            <strong>For Petsfolio Private Limited</strong><br><br><br>
            ____________________________________<br>
            <strong>Authorized Signatory</strong><br>
            Date: <?= $currentDate ?>
          </td>
          <td style="width: 10%;"></td>
          <td style="width: 45%; vertical-align: top;">
            <strong>Service Provider</strong><br><br><br>
            ____________________________________<br>
            <strong>Signature :</strong> <?= $provider->name ?><br>
            <strong>Date:</strong> <?= $currentDate ?>
          </td>
        </tr>
      </table>

    </div>
  </div>
</body>

</html>