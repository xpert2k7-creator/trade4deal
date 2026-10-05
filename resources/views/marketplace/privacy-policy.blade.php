@extends('layouts.marketplace')

@section('title', 'Privacy Policy - Trade4Deal')

@push('styles')
<style>
    .legal-hero {
        background: linear-gradient(135deg, #061f49 0%, #064475 62%, #0e7490 100%);
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.14);
    }

    .legal-hero-inner {
        max-width: 980px;
        padding: clamp(3rem, 7vw, 5.25rem) 0;
    }

    .legal-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 0.8rem;
        font-weight: 850;
        text-transform: uppercase;
    }

    .legal-hero h1 {
        margin: 1rem 0 0.8rem;
        color: #fff;
        font-size: clamp(2.2rem, 5vw, 3.6rem);
        font-weight: 850;
        line-height: 1.08;
    }

    .legal-hero p {
        max-width: 820px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 1rem;
        line-height: 1.7;
    }

    .legal-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
        margin-top: 1.4rem;
    }

    .legal-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.58rem 0.78rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.86);
        font-size: 0.88rem;
        font-weight: 750;
    }

    .legal-shell {
        background: #f5f7fb;
    }

    .legal-document {
        max-width: 980px;
        margin: 0 auto;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        background: #fff;
        padding: clamp(1.35rem, 4vw, 3rem);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
    }

    .legal-document h2 {
        margin: 2rem 0 0.85rem;
        color: #061224;
        font-size: clamp(1.18rem, 2.2vw, 1.55rem);
        font-weight: 850;
        line-height: 1.3;
    }

    .legal-document h2:first-child {
        margin-top: 0;
    }

    .legal-document h3 {
        margin: 1.35rem 0 0.65rem;
        color: #0f2d4c;
        font-size: 1rem;
        font-weight: 850;
        line-height: 1.35;
    }

    .legal-document p,
    .legal-document li {
        color: #475569;
        font-size: 0.96rem;
        line-height: 1.72;
    }

    .legal-document p {
        margin: 0 0 0.95rem;
    }

    .legal-document ul {
        margin: 0 0 1.1rem;
        padding-left: 1.25rem;
    }

    .legal-document a {
        color: var(--t4d-primary);
        font-weight: 750;
    }

    .legal-divider {
        height: 1px;
        margin: 2rem 0;
        background: #e2e8f0;
    }

    .legal-contact-card {
        border: 1px solid #dbe3ef;
        border-radius: 8px;
        background: #f8fafc;
        padding: 1.1rem;
    }
</style>
@endpush

@section('content')
<section class="legal-hero">
    <div class="container">
        <div class="legal-hero-inner">
            <span class="legal-eyebrow"><i class="bi bi-lock"></i> Legal</span>
            <h1>Privacy Policy</h1>
            <p>This Privacy Policy explains how Trade4Deal collects, receives, uses, stores, processes, shares and protects information relating to users of our website, platform, applications, services, communications and other digital services.</p>
            <div class="legal-meta">
                <span><i class="bi bi-calendar-check"></i> Effective Date: 20 September 2026</span>
                <span><i class="bi bi-clock-history"></i> Last Updated: 20 September 2026</span>
            </div>
        </div>
    </div>
</section>

<section class="legal-shell py-5">
    <div class="container">
        <article class="legal-document">
            <p><strong>Trade4Deal.com</strong> ("<strong>Trade4Deal</strong>", "<strong>we</strong>", "<strong>us</strong>", "<strong>our</strong>" or "<strong>Platform</strong>") is a global Business-to-Business ("<strong>B2B</strong>") marketplace platform designed to connect buyers, suppliers, manufacturers, exporters, importers, distributors, traders and other business users across different countries and markets.</p>
            <p>By accessing or using Trade4Deal.com, registering an account, submitting an enquiry, posting an RFQ, listing products, communicating with another user, requesting a quotation, subscribing to communications or otherwise using our Services, you acknowledge that you have read and understood this Privacy Policy.</p>
            <p>If you do not agree with this Privacy Policy, please do not use the Services.</p>

            <div class="legal-divider"></div>

            <h2>1. Who This Privacy Policy Applies To</h2>
            <p>This Privacy Policy applies to information relating to:</p>
            <ul>
                <li>Buyers, suppliers, manufacturers, exporters, importers, distributors, wholesalers, traders, agents and representatives.</li>
                <li>Business owners, employees, visitors, registered users and users submitting RFQs, enquiries or product requirements.</li>
                <li>Users listing products or company profiles, users communicating through the Platform, individuals acting on behalf of companies or organisations, business partners and service providers interacting with Trade4Deal.</li>
            </ul>
            <p>Because Trade4Deal is a B2B marketplace, some information submitted by a user may relate to a business rather than an individual. However, where business information identifies or can reasonably be linked to an individual, it may constitute personal data and will be handled accordingly.</p>

            <h2>2. Information We Collect</h2>
            <h3>2.1 Account and Registration Information</h3>
            <p>When you create an account, we may collect full name, business/company name, designation or job title, business email address, mobile or telephone number, country, state/province, city, business address, login credentials, account type, buyer/supplier/importer/exporter status, industry and business category, website and social media/business links, business registration information, tax/GST/VAT information where voluntarily provided or required, and other information necessary to create and manage your business account.</p>

            <h2>3. Business Profile and Company Information</h2>
            <p>Trade4Deal may allow users to create business profiles. A business profile may contain company name, company logo, business description, product categories, products and services, manufacturing/supply capabilities, minimum order quantities, export/import markets, countries served, business location, certifications, licences, company registration details, GST/VAT/tax information, trade-related documents, contact information, website, product images, product specifications, company photographs and other business information voluntarily submitted by the user.</p>
            <p>Some business profile information may be displayed publicly or to other registered users depending on the Platform's functionality and the user's account settings.</p>
            <p>Users should not upload confidential, sensitive or proprietary information unless they have the authority to do so and understand how that information may be displayed or shared.</p>

            <h2>4. Product Listings, RFQs and Business Requirements</h2>
            <p>Trade4Deal may collect and process information submitted through product listings, buyer requirements, supplier offers, Requests for Quotation ("RFQs"), requests for information, purchase requirements, product enquiries, quotations, tender-related information, import/export requirements, MOQ requirements, pricing information, packaging requirements, delivery requirements, shipping requirements, product specifications, quality requirements, certifications, samples and sample requests, and other business-to-business communications.</p>
            <p>Depending on the nature of the Service, certain information may be shared with relevant buyers, suppliers or other business users to facilitate business communication.</p>

            <h2>5. Communication and Enquiry Information</h2>
            <p>When you contact Trade4Deal or communicate through the Platform, we may collect email communications, phone numbers, messages, enquiry details, RFQ information, quotation information, support requests, feedback, complaints, attachments, documents, communication history and information provided during calls or other customer-support interactions, where permitted by applicable law.</p>
            <p>We may use this information to respond to enquiries, provide customer support, facilitate buyer-supplier communication and maintain records of business interactions.</p>

            <h2>6. Verification and Business Due Diligence</h2>
            <p>To improve marketplace integrity and reduce fraud, Trade4Deal may conduct or facilitate business verification. Depending on the service and applicable law, we may request company registration documents, tax registration documents, GST/VAT information, import/export licences, trade licences, business addresses, identity or authorised representative information, bank or payment-related business information, certifications, product certifications and other documents reasonably necessary for verification.</p>
            <p>Trade4Deal may use third-party verification, compliance or fraud-prevention service providers where appropriate. Submission of verification information does not necessarily mean that Trade4Deal guarantees, certifies or warrants the accuracy, legitimacy, financial standing, product quality or performance of a business.</p>

            <h2>7. Payment Information</h2>
            <p>Where Trade4Deal provides payment-related functionality, payment information may include billing information, invoice information, transaction references, payment status, payment method information and bank or financial information required for a transaction.</p>
            <p>Where payments are processed by third-party payment providers, payment card or other sensitive payment information may be collected and processed directly by the relevant payment service provider in accordance with its own privacy policy and terms. Trade4Deal may receive limited payment-related information necessary to confirm, reconcile, administer or support a transaction.</p>

            <h2>8. Device, Technical and Usage Information</h2>
            <p>When you access the Platform, we may automatically collect certain technical information, including IP address, browser type, device type, operating system, device identifiers, language preference, time zone, approximate location derived from technical information where permitted, pages visited, search activity, referring pages, clicks and interactions, session information, login times, error logs, performance information and security logs.</p>
            <p>This information may be used for security, analytics, performance improvement, fraud prevention and service optimisation.</p>

            <h2>9. Cookies and Similar Technologies</h2>
            <p>Trade4Deal may use cookies, pixels, tags, local storage and similar technologies to keep users signed in, remember preferences, maintain sessions, improve website functionality, understand website usage, analyse traffic, improve user experience, measure advertising performance, detect fraud and abuse, improve security, personalise certain features and support marketing activities where permitted.</p>
            <p>Depending on applicable law and the type of cookie, Trade4Deal may request consent before placing or using certain cookies. You may be able to control cookies through your browser settings or through a cookie-consent mechanism provided on the Platform. Disabling certain cookies may affect some Platform functionality.</p>

            <h2>10. How We Use Information</h2>
            <h3>A. Providing the Platform</h3>
            <p>We may use information for creating and managing accounts, providing marketplace functionality, publishing business profiles, publishing product listings, connecting buyers and suppliers, facilitating RFQs and enquiries, facilitating quotations, enabling business communications and providing customer support.</p>
            <h3>B. Marketplace Operations</h3>
            <p>We may use information for matching buyers with relevant suppliers, matching suppliers with relevant business requirements, improving search and discovery, facilitating introductions, supporting business opportunities, monitoring marketplace activity and improving marketplace quality.</p>
            <h3>C. Security and Fraud Prevention</h3>
            <p>We may use information for detecting fraudulent activity, preventing abuse, protecting accounts, detecting unauthorised access, investigating suspicious activities, protecting the Platform and users and maintaining cybersecurity.</p>
            <h3>D. Business and Analytics Purposes</h3>
            <p>We may use information for understanding marketplace trends, measuring Platform performance, improving products and services, conducting analytics, developing new features, understanding user behaviour and preparing aggregated and statistical reports.</p>
            <h3>E. Communication and Marketing</h3>
            <p>Where permitted by law and subject to applicable consent requirements, Trade4Deal may send service notifications, account notifications, RFQ notifications, business opportunity notifications, product updates, Platform updates, newsletters, promotional communications, events and webinar information and relevant business offers. Users may opt out of promotional communications at any time through the available unsubscribe mechanism or by contacting us.</p>

            <h2>11. Buyer-Supplier Information Sharing</h2>
            <p>A central purpose of Trade4Deal is to facilitate communication between businesses. When a buyer submits an enquiry or RFQ, relevant information may be shared with appropriate suppliers. When a supplier responds to an enquiry or RFQ, relevant information may be shared with the buyer.</p>
            <p>Depending on the Platform's features, this may include name, company name, business location, business email, telephone/mobile number, product requirements, product information, quantity, MOQ, specifications, delivery requirements, commercial requirements and other information necessary to facilitate the business opportunity.</p>
            <p>Users should assume that information intentionally submitted for marketplace interaction may be visible to relevant business users. Trade4Deal does not guarantee that another user will keep information confidential after it has been shared with that user.</p>

            <h2>12. Publicly Available Information</h2>
            <p>Certain information may be displayed publicly if a user chooses to publish it through a business profile, product listing, company page or other public-facing feature. Publicly available information may potentially be viewed, copied, indexed or used by third parties. Users should therefore avoid publishing personal, confidential, proprietary or commercially sensitive information unless they intend to make that information publicly available.</p>

            <h2>13. Information From Third Parties</h2>
            <p>Trade4Deal may receive information from third parties, including business verification providers, payment providers, analytics providers, advertising partners, fraud-prevention providers, technology providers, business partners, publicly available business directories, corporate websites, trade databases and other users. Where permitted by applicable law, we may combine information received from third parties with information collected through the Platform.</p>

            <h2>14. Third-Party Service Providers</h2>
            <p>Trade4Deal may use third-party service providers for cloud hosting, website infrastructure, data storage, email delivery, SMS/communication services, analytics, customer support, payment processing, business verification, fraud detection, cybersecurity, advertising, marketing automation, CRM services, search functionality, website performance, authentication and document management.</p>
            <p>These providers may process information on our behalf and may have access to information only to the extent reasonably necessary to provide their services, subject to applicable contractual and legal requirements.</p>

            <h2>15. International Data Transfers</h2>
            <p>Trade4Deal is a global B2B marketplace and may connect businesses located in different countries. Your information may therefore be stored, accessed or processed in India or other countries where Trade4Deal, its affiliates, technology providers or service providers operate.</p>
            <p>Where required by applicable law, Trade4Deal will take appropriate measures relating to international transfers of personal data. By using the Platform, you acknowledge that your information may be processed across national borders where permitted by applicable law.</p>

            <h2>16. Data Retention</h2>
            <p>Trade4Deal will retain information for as long as reasonably necessary for providing the Services, maintaining user accounts, facilitating business transactions, maintaining business and communication records, security and fraud prevention, compliance with legal obligations, resolving disputes, enforcing agreements, protecting legal rights and legitimate business purposes.</p>
            <p>When information is no longer reasonably required, Trade4Deal may delete, anonymise or securely dispose of it, subject to applicable legal, regulatory, contractual and operational requirements. Different categories of information may have different retention periods.</p>

            <h2>17. Data Security</h2>
            <p>Trade4Deal takes reasonable technical and organisational measures designed to protect information against unauthorised access, unauthorised disclosure, loss, misuse, alteration, destruction and unauthorised processing.</p>
            <p>Security measures may include access controls, authentication mechanisms, encryption where appropriate, monitoring, logging, backups, secure infrastructure and other safeguards appropriate to the nature of the information. However, no electronic transmission or storage system can be guaranteed to be completely secure. Users are responsible for maintaining the confidentiality of their passwords, login credentials and account information.</p>

            <h2>18. Data Breaches and Security Incidents</h2>
            <p>If Trade4Deal becomes aware of a security incident affecting personal data, we will take appropriate steps to investigate, contain and mitigate the incident and provide notices where required by applicable law. Where required, Trade4Deal may notify affected users, regulators or other relevant authorities in accordance with applicable legal requirements.</p>

            <h2>19. User Rights</h2>
            <p>Subject to applicable law, users may have rights relating to their personal data, including the right to access information about processing, request correction of inaccurate information, request updating or completion of information, request deletion or erasure where legally applicable, withdraw consent where consent is the legal basis for processing, opt out of certain marketing communications, raise a privacy-related grievance and exercise other rights available under applicable law.</p>
            <p>Requests may be subject to verification to protect the account holder and prevent unauthorised access. Some requests may not be immediately fulfilled where retention or processing is required by law or for legitimate purposes such as fraud prevention, dispute resolution or legal compliance.</p>

            <h2>20. Withdrawal of Consent</h2>
            <p>Where processing is based on consent, users may withdraw their consent, subject to applicable law. Withdrawal of consent will not affect the legality of processing carried out before the withdrawal. Withdrawal may also affect our ability to provide certain Services or features where the relevant information is necessary for providing those Services.</p>

            <h2>21. Marketing Communications</h2>
            <p>Trade4Deal may communicate with users regarding Platform services, business opportunities, RFQs, product categories, supplier opportunities, buyer opportunities, industry updates, events, newsletters and promotional campaigns. Users may unsubscribe from promotional emails using the unsubscribe option included in the communication or by contacting Trade4Deal. Transactional, security or essential service communications may continue where necessary.</p>

            <h2>22. Business Communications</h2>
            <p>Trade4Deal may facilitate communication between users by email, telephone, messaging systems, notifications or other communication channels. Trade4Deal may retain communication records for customer support, fraud prevention, dispute investigation, security, Platform improvement, compliance and marketplace quality. Users should exercise appropriate caution before sharing confidential commercial information with another business.</p>

            <h2>23. User-Generated Content</h2>
            <p>Users may submit product descriptions, company descriptions, images, videos, documents, certifications, product catalogues, RFQs, reviews, business information, messages and other content. Users are responsible for ensuring that they have the legal right and authority to submit such information. Users should not submit personal data belonging to another person without appropriate authority or legal basis.</p>

            <h2>24. Information Relating to Other Individuals</h2>
            <p>If you provide Trade4Deal with information relating to an employee, customer, representative, director, supplier or another individual, you confirm that you have the necessary authority to provide that information and, where required, have provided appropriate notice or obtained the required permission.</p>

            <h2>25. Children's Privacy</h2>
            <p>Trade4Deal is intended primarily for businesses and business users. The Platform is not intended to be used by children for independent commercial activity. We do not knowingly seek to collect personal data from children where such collection is prohibited by applicable law. If you believe that a child has provided personal information to Trade4Deal improperly, please contact us so that appropriate action can be considered.</p>

            <h2>26. Fraud, Abuse and Marketplace Safety</h2>
            <p>Trade4Deal may process information to identify or investigate fake accounts, fraudulent businesses, misrepresentation, spam, phishing, suspicious transactions, identity misuse, unauthorised access, abusive behaviour, unlawful activities and attempts to manipulate the marketplace. Trade4Deal may suspend, restrict or terminate accounts where permitted under applicable law and our Terms of Use.</p>

            <h2>27. Business Verification Does Not Guarantee a Transaction</h2>
            <p>Trade4Deal may display verification indicators or collect business information for marketplace integrity. However, verification does not necessarily constitute a guarantee regarding financial capacity, product quality, product authenticity, legal status, creditworthiness, delivery performance, regulatory compliance, business reputation, solvency or contractual performance. Users should conduct their own commercial and legal due diligence before entering into transactions.</p>

            <h2>28. Third-Party Websites and Services</h2>
            <p>Trade4Deal may contain links to third-party websites, payment providers, logistics providers, social media platforms or other services. Trade4Deal is not responsible for the privacy practices, security, content or policies of third-party websites or services. Users should review the applicable privacy policies of third-party services before providing information to them.</p>

            <h2>29. Social Media</h2>
            <p>Trade4Deal may maintain pages or accounts on social media platforms. If you interact with Trade4Deal through social media, the relevant social media provider may collect and process information according to its own privacy policy.</p>

            <h2>30. Analytics</h2>
            <p>Trade4Deal may use analytics tools to understand number of visitors, traffic sources, popular pages, search behaviour, user interactions, Platform performance, conversion activity and technical performance. Analytics information may be aggregated or otherwise used to improve the Platform.</p>

            <h2>31. Personalisation and Recommendations</h2>
            <p>Trade4Deal may use information relating to user activity, product interests, searches, RFQs, categories and business preferences to improve marketplace discovery and provide relevant recommendations. For example, the Platform may suggest potentially relevant suppliers to buyers or potentially relevant buyer requirements to suppliers. Such recommendations are intended to improve marketplace functionality and do not constitute an endorsement, guarantee or certification of any business.</p>

            <h2>32. Artificial Intelligence and Automated Technologies</h2>
            <p>Where Trade4Deal uses artificial intelligence, machine-learning systems or automated technologies, such systems may be used for search improvement, product categorisation, matching buyers and suppliers, recommendation systems, spam and fraud detection, content moderation, customer support and marketplace analytics. Where required by applicable law, Trade4Deal will provide appropriate information or mechanisms relating to such processing.</p>

            <h2>33. Legal Disclosures</h2>
            <p>Trade4Deal may disclose information where reasonably necessary to comply with applicable laws, respond to lawful government requests, comply with court orders, protect users, investigate fraud, prevent cybercrime, protect Trade4Deal's legal rights, enforce contracts, investigate suspected unlawful activity and protect the security of the Platform. Such disclosure will be made in accordance with applicable law.</p>

            <h2>34. Corporate Transactions</h2>
            <p>If Trade4Deal is involved in a merger, acquisition, restructuring, financing, sale of assets, investment transaction or similar corporate event, information may be transferred as part of that transaction, subject to applicable law.</p>

            <h2>35. Aggregated and Anonymised Information</h2>
            <p>Trade4Deal may create aggregated, statistical or anonymised information from data collected through the Platform. Such information may be used for business analytics, market research, industry reports, Platform improvement, commercial planning, product development and marketing and promotional purposes. Where information has been properly anonymised, it may no longer constitute personal data under applicable law.</p>

            <h2>36. Confidential Business Information</h2>
            <p>Trade4Deal is a marketplace platform and is not automatically a confidential information repository. Users should not upload trade secrets, confidential pricing strategies, proprietary formulas, intellectual property, unpublished financial information or other highly confidential business information unless the relevant feature or agreement specifically provides appropriate confidentiality protection. Users remain responsible for determining what information they should disclose to another business.</p>

            <h2>37. User Responsibility for Account Security</h2>
            <p>Users must maintain the confidentiality of passwords, use strong passwords, not share login credentials, notify Trade4Deal of suspected unauthorised access, provide accurate account information, keep account information updated, not impersonate another person or company and not use another user's account without permission. Trade4Deal is not responsible for unauthorised activity resulting from a user's failure to adequately secure their account, except to the extent otherwise required by applicable law.</p>

            <h2>38. Accuracy of Information</h2>
            <p>Users are responsible for ensuring that information submitted to Trade4Deal is accurate, current and not misleading. Trade4Deal may allow users to update, correct or remove information through their account or by contacting us.</p>

            <h2>39. Deletion of Account</h2>
            <p>Users may request account deletion subject to applicable law and any outstanding legal, contractual, security or compliance requirements. Deleting an account may not result in immediate deletion of every record where Trade4Deal is legally required or reasonably permitted to retain certain information. Certain business listings, transaction records, communications or other information may continue to exist in anonymised, aggregated or legally retained form.</p>

            <h2>40. Grievance Redressal</h2>
            <p>If you have a privacy-related concern, complaint or request, you may contact Trade4Deal using the contact details provided below. We will review and respond to privacy-related requests in accordance with applicable law. Where applicable, users may also have rights to escalate unresolved matters to the relevant regulatory authority or data protection authority.</p>

            <h2>41. Privacy Contact / Data Protection Contact</h2>
            <div class="legal-contact-card">
                <p><strong>Trade4Deal.com</strong><br>
                    <strong>Email:</strong> <a href="mailto:info@trade4deal.com">info@trade4deal.com</a><br>
                    <strong>Website:</strong> <a href="http://www.trade4deal.com">www.trade4deal.com</a><br>
                    <strong>Phone:</strong> +91 91422 72080</p>
                <p><strong>Privacy / Data Protection Contact:</strong> [Insert designated person/name]<br>
                    <strong>Registered Office:</strong> [Insert complete registered office address]<br>
                    <strong>Grievance Officer:</strong> [Insert name/designation]<br>
                    <strong>Grievance Email:</strong> [Insert dedicated grievance email, if applicable]</p>
            </div>
            <p class="mt-3">Trade4Deal may update these contact details from time to time.</p>

            <h2>42. Privacy Requests</h2>
            <p>When submitting a privacy request, we may ask for information necessary to verify your identity or authority to make the request. This verification is intended to protect users from unauthorised access, deletion or disclosure of personal information.</p>

            <h2>43. Governing Law and Applicable Regulations</h2>
            <p>This Privacy Policy shall be interpreted in accordance with applicable laws and regulations. For users and processing activities connected with India, applicable Indian laws relating to digital personal data protection, information technology, cybersecurity and electronic communications may apply, including the Digital Personal Data Protection Act, 2023 and applicable rules and regulations as and when they apply to the relevant processing activity.</p>
            <p>For users located outside India, additional privacy or data-protection laws may apply depending on the user's jurisdiction and the nature of the processing. Where legally required, Trade4Deal will take appropriate measures to comply with applicable privacy obligations.</p>

            <h2>44. European Economic Area, United Kingdom and Other Jurisdictions</h2>
            <p>Where Trade4Deal offers Services to individuals or businesses in jurisdictions having additional data-protection requirements, including the European Economic Area ("EEA"), United Kingdom or other applicable jurisdictions, additional rights and obligations may apply.</p>
            <p>Where applicable, users may have rights concerning access, rectification, erasure, restriction of processing, data portability, objection to processing, withdrawal of consent, automated decision-making and marketing communications. Such rights will be handled in accordance with the applicable law governing the relevant processing activity.</p>

            <h2>45. Changes to This Privacy Policy</h2>
            <p>Trade4Deal may update this Privacy Policy from time to time. Changes may be made to reflect new Platform features, changes in business operations, changes in technology, changes in applicable laws, regulatory requirements, changes in data-processing practices and security requirements.</p>
            <p>When significant changes are made, Trade4Deal may provide an appropriate notice through the Platform, email or other legally permitted means. The updated Privacy Policy will be published on this page with the revised "Last Updated" date.</p>

            <h2>46. Severability</h2>
            <p>If any provision of this Privacy Policy is found to be invalid or unenforceable under applicable law, the remaining provisions will continue to the extent permitted by law.</p>

            <h2>47. Entire Privacy Notice</h2>
            <p>This Privacy Policy explains Trade4Deal's general approach to the collection and processing of information through its Services. Additional notices may apply to specific products, services, events, applications, payment services, verification processes or other features. Where a specific privacy notice conflicts with this Privacy Policy for a particular processing activity, the specific notice will apply to that activity to the extent permitted by applicable law.</p>

            <div class="legal-divider"></div>

            <h2>Contact Us</h2>
            <div class="legal-contact-card">
                <p>For questions regarding this Privacy Policy or the processing of your personal information, please contact:</p>
                <p><strong>Trade4Deal.com</strong><br>
                    <strong>Email:</strong> <a href="mailto:info@trade4deal.com">info@trade4deal.com</a><br>
                    <strong>Phone:</strong> +91 91422 72080<br>
                    <strong>Website:</strong> <a href="http://www.trade4deal.com">www.trade4deal.com</a></p>
                <p><strong>Registered Office:</strong> [Insert Registered Office Address]<br>
                    <strong>Privacy / Data Protection Contact:</strong> [Insert Name / Designation]<br>
                    <strong>Grievance Officer:</strong> [Insert Name / Designation]<br>
                    <strong>Grievance Email:</strong> [Insert Email Address]</p>
            </div>

            <p class="mt-4 mb-0"><strong>Last Updated: 20 September 2026</strong></p>
        </article>
    </div>
</section>
@endsection
