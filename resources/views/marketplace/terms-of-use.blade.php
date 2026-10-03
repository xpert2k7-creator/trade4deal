@extends('layouts.marketplace')

@section('title', 'Terms & Conditions - Trade4Deal')

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

    .legal-document p,
    .legal-document li {
        color: #475569;
        font-size: 0.96rem;
        line-height: 1.72;
    }

    .legal-document p {
        margin: 0 0 0.95rem;
    }

    .legal-document ul,
    .legal-document ol {
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
            <span class="legal-eyebrow"><i class="bi bi-file-earmark-text"></i> Legal</span>
            <h1>Terms &amp; Conditions</h1>
            <p>These Terms &amp; Conditions govern your access to and use of the Trade4Deal website, marketplace, applications, digital services, communication tools, business directories, RFQ systems, product listings, company profiles and other services.</p>
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
            <p>Welcome to <strong>Trade4Deal.com</strong>.</p>
            <p>These Terms &amp; Conditions ("<strong>Terms</strong>", "<strong>Terms of Use</strong>" or "<strong>Agreement</strong>") govern your access to and use of the Trade4Deal website, marketplace, applications, digital services, communication tools, business directories, RFQ systems, product listings, company profiles and other services made available by Trade4Deal (collectively, the "<strong>Platform</strong>" or "<strong>Services</strong>").</p>
            <p>Trade4Deal is a global <strong>Business-to-Business ("B2B") marketplace platform</strong> intended to facilitate connections between buyers, suppliers, manufacturers, exporters, importers, distributors, wholesalers, traders and other business users.</p>
            <p>By accessing, registering on, browsing or using the Platform, you agree to be legally bound by these Terms, our Privacy Policy and any additional terms applicable to specific Services.</p>
            <p>If you do not agree with these Terms, you must not access or use the Platform.</p>

            <div class="legal-divider"></div>

            <h2>1. About Trade4Deal</h2>
            <p>Trade4Deal is a digital B2B marketplace designed to facilitate business discovery, sourcing, communication and commercial connections between independent businesses.</p>
            <p>The Platform may provide functionality including buyer registration, supplier registration, company profiles, product listings, product search, supplier discovery, buyer discovery, Requests for Quotation ("RFQs"), buyer requirements, supplier quotations, business enquiries, messaging and communication, product/sample requests, business verification, lead generation, business introductions, advertising, featured listings, premium memberships, market intelligence and other B2B services.</p>
            <p>Unless expressly stated otherwise for a particular service, <strong>Trade4Deal is a marketplace/intermediary platform and not the seller, buyer, manufacturer, importer, exporter, owner, distributor or supplier of products listed by independent users.</strong></p>

            <h2>2. Definitions</h2>
            <p><strong>"Trade4Deal", "we", "us" or "our"</strong> means the entity legally operating the Trade4Deal Platform.</p>
            <p><strong>"User", "you" or "your"</strong> means any individual or legal entity accessing or using the Platform.</p>
            <p><strong>"Buyer"</strong> means a business or person acting on behalf of a business seeking to purchase products or services.</p>
            <p><strong>"Supplier"</strong> means a manufacturer, supplier, exporter, importer, distributor, wholesaler, trader or other business offering products or services.</p>
            <p><strong>"Listing"</strong> means a product, company, service or business listing displayed on the Platform.</p>
            <p><strong>"RFQ"</strong> means a Request for Quotation or business requirement submitted by a Buyer.</p>
            <p><strong>"Content"</strong> includes text, images, videos, documents, logos, product information, company information, quotations, messages and other material submitted to the Platform.</p>
            <p><strong>"Transaction"</strong> means a commercial arrangement or transaction between a Buyer and Supplier.</p>

            <h2>3. Eligibility</h2>
            <p>The Platform is intended primarily for business and commercial users. By using the Platform, you represent that:</p>
            <ol>
                <li>You are legally capable of entering into contracts under applicable law.</li>
                <li>You are using the Platform for legitimate business or commercial purposes.</li>
                <li>If acting on behalf of a company or organisation, you have authority to represent that entity.</li>
                <li>The information provided by you is accurate and complete.</li>
                <li>You will comply with applicable laws and regulations.</li>
                <li>You will not use the Platform for unlawful or fraudulent purposes.</li>
            </ol>
            <p>Trade4Deal may request additional information to verify your identity, business or authority.</p>

            <h2>4. Business Account Registration</h2>
            <p>Certain features may require registration. You may be required to provide name, company name, business email, mobile number, country, business address, business category, designation, company information, product information, registration or tax information and other information reasonably required for the relevant Service.</p>
            <p>You agree to provide accurate and current information. You must promptly update information that becomes inaccurate or outdated.</p>

            <h2>5. Account Security</h2>
            <p>You are responsible for maintaining the confidentiality of your username, password, OTP, authentication credentials and account information.</p>
            <p>You must not share your account credentials, permit unauthorised persons to access your account, use another person's account without permission or attempt to bypass Platform security. If you suspect unauthorised access, you must promptly notify Trade4Deal.</p>

            <h2>6. Business Verification</h2>
            <p>Trade4Deal may provide business verification or supplier verification services. Verification may include review of company registration, GST/VAT information, import/export information, business address, licences, certifications, business documents, contact information and other business information.</p>
            <p>Verification is intended to improve marketplace integrity. However, <strong>verification does not constitute a guarantee or warranty</strong> regarding financial strength, solvency, product quality, product authenticity, product availability, delivery performance, business reputation, legal compliance, creditworthiness or contract performance. Users must independently conduct appropriate commercial due diligence.</p>

            <h2>7. Buyer Responsibilities</h2>
            <p>Buyers are responsible for providing accurate requirements and quantities, specifying product quality requirements, providing correct delivery requirements, reviewing supplier information, conducting supplier due diligence, verifying quotations, confirming product specifications, confirming applicable taxes and duties, confirming shipping and payment terms, obtaining necessary import approvals and complying with applicable laws. A Buyer must not knowingly submit false, misleading or fraudulent requirements.</p>

            <h2>8. Supplier Responsibilities</h2>
            <p>Suppliers are responsible for ensuring that company information, product descriptions, prices, product availability, MOQ information, specifications, certifications, licences, legal compliance, quotations, delivery commitments and export/import requirements are accurate and properly addressed. Suppliers must not list counterfeit, illegal, prohibited or unauthorised products.</p>

            <h2>9. Product Listings</h2>
            <p>Suppliers may list products and services on the Platform. A Listing may include product name, product description, specifications, product images, packaging, MOQ, price or price range, supply capacity, country of origin, certifications, delivery information, contact details and other commercial information. Trade4Deal may edit, reject, suspend or remove Listings that violate these Terms, applicable law or marketplace standards.</p>

            <h2>10. Product Information</h2>
            <p>Trade4Deal may rely on information provided by Suppliers and other Users. Trade4Deal does not ordinarily independently verify every product description, specification, price, certification, stock level, production capacity, claim, photograph, country-of-origin statement or delivery commitment. Users should verify important commercial information directly with the relevant counterparty.</p>

            <h2>11. Request for Quotation (RFQ)</h2>
            <p>Trade4Deal may permit Buyers to submit RFQs. An RFQ may contain product, quantity, quality specification, packaging, destination, delivery requirement, target price, payment preference, required certification, required date and other business requirements. Submission of an RFQ does not create a binding purchase contract unless separately agreed between the Buyer and Supplier.</p>

            <h2>12. Supplier Quotations</h2>
            <p>Suppliers may respond to RFQs with quotations. Unless expressly stated otherwise, quotations are provided directly by Suppliers and not by Trade4Deal. A quotation may be subject to availability, price changes, currency fluctuations, taxes, duties, freight, insurance, production capacity, raw material prices, validity period and other commercial conditions. The Buyer and Supplier are responsible for confirming the final commercial terms.</p>

            <h2>13. Buyer-Supplier Contracts</h2>
            <p>Any contract for the purchase or sale of products or services is between the relevant Buyer and Supplier unless Trade4Deal expressly becomes a contractual party. Trade4Deal does not automatically become a party to purchase orders, sales contracts, supply agreements, distribution agreements, agency agreements, import contracts, export contracts, logistics contracts or payment agreements. Users should execute appropriate written commercial agreements for significant transactions.</p>

            <h2>14. Trade4Deal as Marketplace Facilitator</h2>
            <p>Unless expressly stated otherwise, Trade4Deal does not own the products listed by Suppliers, manufacture the products, take title to the products, guarantee delivery, guarantee payment, guarantee product quality, guarantee transaction completion or guarantee the financial capability of either party. Trade4Deal's primary role is to facilitate business discovery, communication and marketplace connections.</p>

            <h2>15. Transaction Responsibility</h2>
            <p>The Buyer and Supplier are responsible for determining product specifications, quantity, price, currency, payment terms, credit terms, delivery terms, Incoterms, insurance, freight, taxes, duties, customs, inspection, warranty, returns and dispute resolution. Trade4Deal may assist with communication or coordination but does not assume responsibility for the underlying commercial transaction unless expressly agreed.</p>

            <h2>16. Payments</h2>
            <p>Where Trade4Deal provides payment-related Services, applicable payment terms will be separately disclosed. Where payment is made directly between Buyer and Supplier, Trade4Deal is not responsible for payment default, bank delays, fraud by a counterparty, currency fluctuations, chargebacks, banking restrictions or payment disputes. Where third-party payment processors are used, their terms may also apply.</p>

            <h2>17. Trade4Deal Fees</h2>
            <p>Certain Services may be paid Services. These may include premium membership, supplier subscription, buyer subscription, featured listings, sponsored listings, advertising, lead-generation services, RFQ services, verification services, market intelligence, transaction facilitation and other premium Services. Applicable prices will be displayed or communicated before purchase where required.</p>

            <h2>18. Subscriptions</h2>
            <p>Where subscription Services are offered, fees will be communicated before subscription, subscription periods will be specified, renewal terms will be disclosed, applicable taxes may be charged, cancellation terms will be displayed and access may be suspended for non-payment. Trade4Deal may change subscription plans or pricing subject to applicable law and reasonable notice.</p>

            <h2>19. Taxes</h2>
            <p>Users are responsible for applicable GST, VAT, customs duties, import duties, export duties, withholding taxes, local taxes and other governmental charges. Trade4Deal may collect applicable taxes where legally required.</p>

            <h2>20. International Trade</h2>
            <p>International transactions may involve import licences, export licences, customs clearance, sanctions, embargoes, product-specific regulations, country-of-origin requirements, labelling requirements, food safety requirements, phytosanitary requirements, fumigation requirements, inspection certificates, shipping documentation and other regulatory requirements. Buyers and Suppliers are independently responsible for complying with laws applicable to their transactions.</p>

            <h2>21. Food, FMCG and Agricultural Products</h2>
            <p>Where the Platform is used for food, FMCG, agricultural commodities or related products, Users must ensure compliance with applicable laws relating to food safety, labelling, packaging, quality, shelf life, storage, transportation, import/export, certifications, product registration and country-specific standards. Trade4Deal does not automatically certify products merely because they appear on the Platform.</p>

            <h2>22. Prohibited Products</h2>
            <p>Users must not use the Platform to promote, sell, purchase or facilitate unlawful products. Trade4Deal may prohibit or restrict categories including counterfeit goods, stolen goods, illegal drugs, unauthorised pharmaceuticals, weapons, explosives, terrorist-related goods, hazardous materials, products prohibited by applicable law, products violating intellectual-property rights and other restricted categories. Trade4Deal may publish a separate Prohibited &amp; Restricted Products Policy.</p>

            <h2>23. Fraud and Misrepresentation</h2>
            <p>Users must not create fake accounts, impersonate companies, misrepresent ownership, provide fake documents, publish fraudulent quotations, manipulate reviews, use stolen identities, engage in payment fraud, conduct phishing, send malicious links, manipulate RFQs, misrepresent products or conduct fraudulent transactions. Trade4Deal may suspend or terminate accounts suspected of fraudulent activity.</p>

            <h2>24. Anti-Bypass / Circumvention</h2>
            <p>Where a User is introduced to a business opportunity through a paid Trade4Deal Service, the User must not knowingly use the Platform's confidential commercial information to circumvent agreed Trade4Deal fees or commercial arrangements. This provision does not prohibit legitimate independent business relationships or communications where no contractual restriction applies. Specific anti-circumvention obligations may apply to particular paid Services or agreements.</p>

            <h2>25. User Content</h2>
            <p>Users retain ownership of content they lawfully own and submit to the Platform. By submitting Content, you grant Trade4Deal a non-exclusive, worldwide, royalty-free licence, to the extent reasonably necessary to operate the Platform, to host, store, reproduce, display, format, translate, distribute through Platform functionality, promote your Listing, create technical derivatives and use the Content for marketplace operation. This licence continues for as long as reasonably necessary for the relevant purpose, subject to applicable law and our Privacy Policy.</p>

            <h2>26. User Representation Regarding Content</h2>
            <p>By submitting Content, you represent that you own it or have appropriate rights, have authority to submit it, it does not infringe third-party rights, it is not knowingly false or misleading, it does not contain unlawful material and it complies with applicable law.</p>

            <h2>27. Intellectual Property</h2>
            <p>Trade4Deal and its licensors own or control rights in Trade4Deal branding, website design, logos, software, Platform technology, database structure, text created by Trade4Deal, graphics, user interface, Trade4Deal trademarks and other proprietary materials. Users may not copy, reproduce, modify, reverse engineer, scrape or commercially exploit Trade4Deal intellectual property without written permission, except as permitted by law.</p>

            <h2>28. Trademarks</h2>
            <p>"Trade4Deal", Trade4Deal logos, names, slogans and related branding may be protected intellectual property. Users may not use Trade4Deal branding in a manner that suggests official endorsement, partnership, sponsorship, certification or ownership unless Trade4Deal has expressly authorised such use.</p>

            <h2>29. Reviews and Ratings</h2>
            <p>Where Trade4Deal permits reviews or ratings, Users must provide genuine and relevant feedback. Users must not post fake reviews, pay others for deceptive reviews, threaten users with negative reviews, manipulate ratings, review competitors deceptively or post defamatory or unlawful content. Trade4Deal may moderate or remove reviews that violate Platform rules or applicable law.</p>

            <h2>30. Communications</h2>
            <p>Trade4Deal may communicate with Users through email, SMS, WhatsApp or similar channels where lawfully used, telephone, Platform notifications, in-app messaging and other electronic channels. Communications may relate to account activity, RFQs, quotations, business opportunities, security, Platform updates, customer support and marketing where permitted.</p>

            <h2>31. Marketing</h2>
            <p>Users may receive promotional communications where permitted by applicable law. Users may opt out of promotional communications through available unsubscribe mechanisms. Trade4Deal may continue sending essential transactional, security or account-related communications.</p>

            <h2>32. Privacy</h2>
            <p>Trade4Deal's collection and processing of personal information is governed by the Trade4Deal Privacy Policy. The Privacy Policy forms part of these Terms. By using the Platform, Users acknowledge that information may be processed in accordance with the Privacy Policy and applicable law.</p>

            <h2>33. Cookies</h2>
            <p>Trade4Deal may use cookies and similar technologies. Use of cookies is governed by the Trade4Deal Cookie Policy and applicable consent requirements.</p>

            <h2>34. Third-Party Services</h2>
            <p>The Platform may integrate or link to third-party services including payment providers, logistics providers, verification services, analytics providers, communication services, cloud providers, social media and external business services. Trade4Deal does not control the policies or practices of independent third parties. Users should review the applicable third-party terms before using those services.</p>

            <h2>35. Logistics</h2>
            <p>Where Trade4Deal facilitates logistics or connects Users with logistics providers, the relevant logistics provider may have separate terms. Unless expressly agreed otherwise, Trade4Deal does not guarantee shipping time, freight availability, customs clearance, delivery, cargo condition, insurance coverage, port handling or carrier performance.</p>

            <h2>36. Product Inspection</h2>
            <p>Trade4Deal may offer or facilitate inspection services through third parties. An inspection does not necessarily guarantee future product quality, entire shipment quality, supplier performance, delivery or authenticity beyond the scope of inspection. The scope of each inspection service will depend on the applicable agreement.</p>

            <h2>37. Samples</h2>
            <p>Where Suppliers provide samples, Buyers and Suppliers are responsible for agreeing sample charges, shipping costs, sample specifications, testing, return arrangements and approval standards. Trade4Deal does not guarantee that a production shipment will exactly match a sample unless expressly agreed between the contracting parties.</p>

            <h2>38. Availability of Platform</h2>
            <p>Trade4Deal aims to maintain reliable Services but does not guarantee uninterrupted access. The Platform may become temporarily unavailable due to maintenance, upgrades, cybersecurity incidents, network failures, hosting failures, force majeure, third-party failures, government restrictions and other circumstances beyond reasonable control.</p>

            <h2>39. Platform Modifications</h2>
            <p>Trade4Deal may add features, remove features, modify functionality, change user interfaces, change pricing, modify marketplace categories, suspend specific Services and introduce new Services. Where legally required, appropriate notice will be provided.</p>

            <h2>40. Account Suspension</h2>
            <p>Trade4Deal may suspend or restrict an account where reasonably necessary because of Terms violations, fraud, security risks, false information, unlawful activity, payment defaults, abuse, spam, repeated complaints, intellectual-property violations, prohibited products or regulatory requirements. Where appropriate, Trade4Deal may provide an opportunity to address the issue.</p>

            <h2>41. Account Termination</h2>
            <p>Trade4Deal may terminate accounts in accordance with these Terms and applicable law. Users may request account closure. Termination does not automatically extinguish obligations that arose before termination.</p>

            <h2>42. Consequences of Termination</h2>
            <p>Following termination, Platform access may cease, Listings may be removed, Premium Services may end, business connections may no longer be accessible and certain records may be retained where required or permitted by law. Trade4Deal may retain information necessary for legal, security, accounting, fraud-prevention or dispute-resolution purposes.</p>

            <h2>43. Disclaimer of Warranties</h2>
            <p>To the maximum extent permitted by applicable law, the Platform is provided on an "as available" and "as is" basis. Trade4Deal does not warrant that the Platform will always be available, Listings are always accurate, every Supplier or Buyer is legitimate, products meet a particular quality, transactions will be completed, Buyers will pay, Suppliers will deliver, products will meet expectations or commercial opportunities will result from use of the Platform.</p>

            <h2>44. No Guarantee of Business Results</h2>
            <p>Trade4Deal does not guarantee sales, leads, orders, export opportunities, import opportunities, revenue, profits, contracts, customer acquisition or supplier acquisition. Marketplace results depend on numerous factors outside Trade4Deal's control.</p>

            <h2>45. User Due Diligence</h2>
            <p>Users are responsible for conducting appropriate due diligence before entering into a commercial relationship. This may include company verification, references, financial checks, product testing, sample approval, factory inspection, certification verification, contract review, payment-risk assessment and import/export compliance checks.</p>

            <h2>46. Limitation of Liability</h2>
            <p>To the maximum extent permitted by applicable law, Trade4Deal shall not be liable for indirect, incidental, special, consequential or punitive losses arising from buyer-supplier transactions, product defects, supplier default, buyer default, payment disputes, delivery delays, customs issues, regulatory issues, loss of profits, loss of business opportunities, loss of goodwill or loss of data caused by circumstances beyond reasonable control. Nothing in these Terms excludes liability that cannot legally be excluded or limited.</p>

            <h2>47. Indemnification</h2>
            <p>To the maximum extent permitted by applicable law, you agree to indemnify and hold harmless Trade4Deal, its officers, directors, employees, affiliates and service providers from claims, losses, liabilities, damages, costs and expenses arising from your violation of these Terms, unlawful activity, Content, products, business transactions, violation of third-party rights, misrepresentation, breach of applicable law or misuse of the Platform.</p>

            <h2>48. Force Majeure</h2>
            <p>Trade4Deal will not be responsible for failure or delay caused by circumstances beyond reasonable control, including natural disasters, flood, earthquake, fire, war, terrorism, civil unrest, government action, sanctions, epidemics/pandemics, internet failures, cyberattacks, power failures, telecommunications failures, labour disruptions and transportation disruptions.</p>

            <h2>49. Disputes Between Buyers and Suppliers</h2>
            <p>Trade4Deal encourages Buyers and Suppliers to resolve commercial disputes directly. Disputes may include quality, quantity, price, payment, delivery, damage, specifications, contract performance, refunds, customs and logistics. Trade4Deal may, but is not necessarily required to, assist with communication or facilitation. Unless expressly agreed otherwise, Trade4Deal is not the contracting party to the Buyer-Supplier transaction.</p>

            <h2>50. Dispute Resolution With Trade4Deal</h2>
            <p>Any dispute between a User and Trade4Deal should first be raised through the designated grievance or support channel. The parties should attempt good-faith resolution before commencing formal proceedings, subject to applicable law.</p>

            <h2>51. Governing Law</h2>
            <p>These Terms shall be governed by the laws applicable to the Trade4Deal operating entity, subject to mandatory laws applicable to the User or transaction. For the Indian operating entity, applicable Indian laws may apply.</p>

            <h2>52. Jurisdiction</h2>
            <p>Subject to applicable law, disputes involving Trade4Deal may be subject to the jurisdiction of the competent courts at: <strong>[INSERT CITY, STATE, INDIA]</strong></p>
            <p>Trade4Deal should replace this placeholder with the actual agreed jurisdiction after legal review. Nothing in this clause is intended to remove any mandatory legal rights or jurisdiction that cannot lawfully be excluded.</p>

            <h2>53. Arbitration</h2>
            <p>Where appropriate and legally permissible, Trade4Deal may provide for arbitration through a separate agreement or applicable Service terms. Any arbitration provision should specify seat, venue, applicable arbitration law, number of arbitrators, appointment mechanism, language and applicable rules. A final arbitration clause should be inserted only after legal review of the Trade4Deal operating entity and commercial structure.</p>

            <h2>54. Grievance Redressal</h2>
            <div class="legal-contact-card">
                <p>Users may submit complaints relating to privacy, Platform misuse, Listings, fraud, intellectual property, prohibited products, account issues and marketplace conduct.</p>
                <p><strong>Trade4Deal Grievance Contact</strong><br>
                    <strong>Email:</strong> <a href="mailto:info@trade4deal.com">info@trade4deal.com</a><br>
                    <strong>Phone:</strong> +91 91422 72080</p>
                <p><strong>Grievance Officer:</strong> [INSERT NAME/DESIGNATION]<br>
                    <strong>Grievance Email:</strong> [INSERT DEDICATED EMAIL]<br>
                    <strong>Registered Office:</strong> [INSERT COMPLETE ADDRESS]</p>
            </div>

            <h2>55. Intellectual Property Complaints</h2>
            <p>If you believe that Content on Trade4Deal infringes your intellectual-property rights, you should provide your name/company name, contact information, description of the intellectual-property right, identification of the allegedly infringing material, evidence of ownership or authority, statement explaining the alleged infringement and other information reasonably required to evaluate the complaint. Trade4Deal may remove or restrict allegedly infringing Content subject to applicable law.</p>

            <h2>56. Anti-Corruption and Ethical Business</h2>
            <p>Users must comply with applicable anti-bribery, anti-corruption and trade laws. Users must not use Trade4Deal to facilitate bribery, kickbacks, fraud, money laundering, sanctions evasion, corruption or other unlawful commercial activity.</p>

            <h2>57. Sanctions and Export Controls</h2>
            <p>Users involved in international trade are responsible for complying with applicable sanctions, embargoes, export-control and import-control requirements. Trade4Deal may restrict or terminate activities that create legal or compliance risks.</p>

            <h2>58. Scraping and Automated Access</h2>
            <p>Without written permission, Users must not use bots, crawlers, scrapers or automated systems to extract large volumes of Platform data, copy supplier databases, copy buyer databases, circumvent technical controls, harvest contact information, republish Platform databases or interfere with Platform operation. Reasonable search-engine indexing may be permitted where technically allowed.</p>

            <h2>59. Cybersecurity</h2>
            <p>Users must not introduce malware, attempt unauthorised access, conduct denial-of-service attacks, circumvent authentication, test vulnerabilities without permission, interfere with Platform infrastructure or upload malicious files. Security vulnerabilities may be reported to Trade4Deal through the appropriate security contact.</p>

            <h2>60. Confidential Information</h2>
            <p>Trade4Deal may facilitate business communications but does not automatically create a confidentiality obligation between Users. Users should execute an appropriate Non-Disclosure Agreement ("NDA") before exchanging highly confidential information where appropriate.</p>

            <h2>61. Records and Electronic Communications</h2>
            <p>Electronic records and communications may be retained and used as evidence of communications or transactions to the extent permitted by applicable law. Users agree that electronic communications may satisfy legal communication requirements where legally valid.</p>

            <h2>62. Changes to These Terms</h2>
            <p>Trade4Deal may update these Terms from time to time because of new features, new Services, changes in business operations, legal requirements, regulatory requirements, security requirements and marketplace developments. The updated version will be published on the Platform with a revised "Last Updated" date. Continued use after the effective date of updated Terms may constitute acceptance where permitted by law.</p>

            <h2>63. Severability</h2>
            <p>If any provision of these Terms is found invalid or unenforceable, the remaining provisions will continue to apply to the extent permitted by law.</p>

            <h2>64. Waiver</h2>
            <p>Failure by Trade4Deal to enforce a provision of these Terms does not constitute a waiver of its right to enforce that provision later.</p>

            <h2>65. Assignment</h2>
            <p>Users may not assign their rights or obligations under these Terms without Trade4Deal's written consent where required. Trade4Deal may assign or transfer its rights and obligations in connection with a merger, acquisition, restructuring or transfer of business, subject to applicable law.</p>

            <h2>66. Entire Agreement</h2>
            <p>These Terms, together with the Privacy Policy and any additional Service-specific terms, constitute the agreement governing use of the Platform, subject to applicable law.</p>

            <h2>67. Language</h2>
            <p>The English version of these Terms shall be the controlling version unless otherwise required by applicable law. Translations may be provided for convenience.</p>

            <h2>68. Contact Information</h2>
            <div class="legal-contact-card">
                <p>For general enquiries:</p>
                <p><strong>Trade4Deal.com</strong><br>
                    <strong>Website:</strong> <a href="http://www.trade4deal.com">www.trade4deal.com</a><br>
                    <strong>Email:</strong> <a href="mailto:info@trade4deal.com">info@trade4deal.com</a><br>
                    <strong>Phone:</strong> +91 91422 72080</p>
                <p><strong>Registered Legal Entity:</strong> [INSERT LEGAL ENTITY NAME]<br>
                    <strong>Registered Office:</strong> [INSERT COMPLETE REGISTERED OFFICE ADDRESS]<br>
                    <strong>Grievance Officer:</strong> [INSERT NAME/DESIGNATION]<br>
                    <strong>Grievance Email:</strong> [INSERT EMAIL]</p>
            </div>

            <h2>69. Acceptance</h2>
            <p>By clicking "Register", "Create Account", "Submit RFQ", "List Product", "Contact Supplier", "Contact Buyer", "Subscribe", "Continue" or a similar acceptance button, or by otherwise accessing or using the Platform, you acknowledge that:</p>
            <ol>
                <li>You have read these Terms.</li>
                <li>You understand these Terms.</li>
                <li>You agree to comply with these Terms.</li>
                <li>You have authority to act on behalf of the business you represent, where applicable.</li>
                <li>You will comply with applicable laws.</li>
                <li>You accept responsibility for your use of the Platform.</li>
            </ol>
            <p><strong>Thank you for using Trade4Deal.com - Connecting Global Buyers &amp; Suppliers.</strong></p>
            <p class="mb-0"><strong>Trade4Deal.com</strong><br><em>Global B2B Marketplace</em></p>
        </article>
    </div>
</section>
@endsection
