@extends('layouts.customer')

@section('title', __('Public offer') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        <section class="public-offer">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <h2 class="public-offer__title">{{__('Public offer')}}</h2>
                        <p class="public-offer__title-desc">{{__('For the provision of services by the "socialbooster.me" service')}}</p>

                        @if(app()->getLocale() == 'en')
                            <p class="public-offer__text">The administration of the site "socialbooster.me" offers you
                                the services of a site located on the Internet at: {{ URL::to('/') }} (hereinafter
                                - the site), on the terms that are the rules for using the site "socialbooster.me".
                                These rules are considered by the Site Administration as a public offer in accordance
                                with Art. 437 of the Civil Code of the Russian Federation.</p>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>1.</span> Terms and definitions
                                </div>
                                <p class="public-offer__title-desc">For the purposes of this offer, the following terms
                                    and definitions are interpreted as follows:</p>

                                <ul>
                                    <li class="public-offer__text">
                                        <span>1.1</span>"Internet resource" - a set of integrated software and hardware
                                        tools and information intended for publication on the Internet and displayed in
                                        a specific text, graphic or sound form. An Internet resource is available to
                                        Internet users through a domain name and URL (Uniform Resource Locator) - a
                                        unique electronic address that allows access to information and a
                                        hardware-software complex.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.2</span>"Internet page" - page (HTML document) of an Internet resource.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.3</span>"Information materials" - any text, graphic, audio, video and
                                        mixed materials of an informational nature
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.4</span>"Placement of Information Materials" - technical placement of
                                        customer information materials on Internet resources.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.5</span>"Service" socialbooster.me "- a software and hardware complex
                                        managed by the Site Administration, designed to fulfill mutual obligations by
                                        the parties.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.6</span>"The user interface of the service" socialbooster.me "is an
                                        interface for accessing statistics and managing the placement of information
                                        materials. Login is carried out using the login (e-mail) and password at: <a
                                                href="{{ URL::to('/') }}" class="link" target="_blank">{{ URL::to('/') }}</a>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.7</span>"User" - any person who has registered on the site
                                        {{ URL::to('/') }} and has agreed to the terms of use of the site.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.8</span>"Client" ("Customer") - an individual or legal entity posting
                                        information materials belonging to it on the basis of ownership or other right
                                        defined by the current legislation of the Russian Federation, on the Internet
                                        through the service "socialbooster.me"
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.9</span>"Contractor" - an individual who has reached the age of 18, is
                                        capable, is a user of the Internet, and is responsible for posting the Client’s
                                        information materials in his own blogs, website pages and other Internet
                                        resources.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.10</span>"Ban" - a temporary limitation of the user's work for a
                                        specific or indefinite period by decision of the Site Administration.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.11</span>
                                        Subscription (recurring payment) - a type of payment that implies an automatic
                                        debiting funds for received services from the payment resources of the User
                                        without the need for any repeated action on his part.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>2.</span> General
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>2.1</span>The use of materials and services of the Site is governed by the
                                        norms of the current legislation of the Russian Federation.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.2</span>Acceptance of this offer is made by sequentially performing the
                                        following actions:
                                        <ul class="list-circle">
                                            <li>familiarization with the terms of this offer and the Terms of Use of the
                                                socialbooster.me service, available on the website
                                                {{ URL::to('/') }};
                                            </li>
                                            <li>filling out a questionnaire published in the form of a registration
                                                form;
                                            </li>
                                            <li>independent determination of the login (e-mail) and password used at
                                                least five characters long, including numbers, upper and lower case
                                                letters of the Latin alphabet;
                                            </li>
                                            <li>submitting the registration form through the interface on the site
                                                {{ URL::to('/') }}.
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.3</span>User is fully responsible for the safety of his username and
                                        password in the socialbooster.me service and for losses that may arise due to
                                        unauthorized use of his username, password and / or access channel. The site
                                        administration is not responsible and does not compensate for losses incurred
                                        due to unauthorized access of third parties to information about the User’s
                                        account.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.4</span>User is not entitled to transfer to third parties a login and
                                        password that allows access to the user interface of the socialbooster.me
                                        service and other services provided by the Site Administration. All actions that
                                        require the use of a username and password are considered committed by the User.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.5</span>The site administration has the right at any time to
                                        unilaterally change the terms of this offer. Such changes take effect after 3
                                        (three) days from the date of posting a new version of the offer on the site. If
                                        the User disagrees with the changes made, he is obliged to refuse access to the
                                        site, stop using the materials and services of the site.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.6</span>Use of the site materials without the consent of the copyright
                                        holders is not allowed (Article 1270 of the Civil Code of the Russian
                                        Federation). When quoting materials from the site, a link to the site is
                                        required (Clause 1, Article 1274 of the Civil Code of the Russian Federation).
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.7</span>Comments and other entries of the User on the site should not
                                        conflict with the requirements of the legislation of the Russian Federation and
                                        generally accepted standards of morality.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.8</span>User agrees that the Site Administration is not responsible and
                                        does not have direct or indirect obligations to the User in connection with any
                                        possible or resulting losses or losses associated with any site content,
                                        copyright registration and information about such registration, goods or
                                        services accessible on the site or obtained through external sites or resources
                                        or other user contacts that he entered into using information on the site or
                                        links to external resources.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.9</span>User accepts the provision that all materials and services of
                                        the site or any part thereof may be accompanied by advertising. The user agrees
                                        that the site Administration does not bear any responsibility and does not have
                                        any obligations in connection with such advertising.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.10</span>The site administration is not responsible to third parties for
                                        the content of the information used in the information materials posted by the
                                        Client, as well as for property, moral or any other damage caused as a result of
                                        the use of this information by third parties.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.11</span>The cost of posting information materials is regulated by the
                                        Site Administration unilaterally and can be changed at any time.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.12</span>The cost of posting informational material also directly
                                        depends on the cost set by the User of the {{ URL::to('/') }} service
                                        itself.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.13</span>The site administration reserves the right to reject any
                                        information material without explanation.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.14</span>The site administration has the right to block the User’s
                                        account if attempts are made to mask the real IP address of the User (for
                                        example, using a network of proxy servers, TOR service or analogues).
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.15</span>When using the Site, the user is prohibited from:
                                        <ol class="list-circle">
                                            <li>register as a User on behalf of or instead of another person ("fake
                                                account");
                                            </li>
                                            <li>mislead Users about their identity using the username and password
                                                of another registered User;
                                            </li>
                                            <li> upload, store, publish, distribute and provide access to or otherwise
                                                use any information that:
                                                <ol class="list-square">
                                                    <li>contains threats, discredits, insults, defames honor and dignity
                                                        or business reputation or violates the privacy of other Users or
                                                        third parties, violates the rights of minors;
                                                    </li>
                                                    <li>is vulgar or obscene, contains foul language, contains
                                                        pornographic images and texts or scenes of a sexual nature
                                                        involving minors, contains scenes of violence or inhuman
                                                        treatment of animals;
                                                    </li>
                                                    <li>contains a description of the means and methods of suicide, any
                                                        incitement to commit it;
                                                    </li>
                                                    <li>advocates and / or promotes incitement to racial, religious,
                                                        ethnic hatred or enmity, promotes fascism or the ideology of
                                                        racial superiority;
                                                    </li>
                                                    <li>contains extremist materials; promotes criminal activity or
                                                        contains advice, instructions or guidelines for committing
                                                        criminal acts;
                                                    </li>
                                                    <li>contains information of limited access, including, but not
                                                        limited to, state and commercial secrets, information about the
                                                        privacy of third parties;
                                                    </li>
                                                    <li>it is fraudulent;</li>
                                                    <li>it also violates other rights and interests of citizens and
                                                        legal entities or the requirements of the legislation of the
                                                        Russian Federation.
                                                    </li>
                                                </ol>
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.16</span>All questions or problems associated with the use of the
                                        socialbooster.me service are resolved in support groups or in the form of
                                        feedback. The site administration considers applications as they become
                                        available.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>3.</span> Rules for using the site for the Contractors:
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>3.1</span>The completed task means the placement by the Contractor of
                                        information material on his page, blog, other Internet resources for a constant
                                        period (until the account is deleted from the system) or a certain action on the
                                        Internet according to the specified criteria.
                                        <p><b>Contractor carries out the placement of information material at his own
                                                risk. The site administration does not bear any responsibility for the
                                                placement of material by the user of the service.</b>
                                        </p>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.2</span>Users whose pages, blogs, other Internet resources on which it
                                        is planned to place information materials of the Client, entirely consist of
                                        advertising materials, are blocked, automatically filled, do not have active
                                        visitors (followers) or a small number of them, and are also false
                                        (multivodstvo) are not allowed to use the socialbooster.me service.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.3</span>Cash remuneration and bonuses received by the User when the
                                        Contractor is theClient (Customer) by another task or order, can be transferred
                                        by internal transfer to the account of the Client (Customer) on the website.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.4</span>The commission when withdrawing funds from an account on the
                                        site can reach 30%, the withdrawal is carried out within 10 working days from
                                        the moment the user submits the application and is confirmed by the site
                                        administration.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.5</span>The site administration has the right to change the percentage
                                        (%) of the commission to replenish the User’s internal account and the
                                        percentage (%) of the commission to withdraw funds from the User’s internal
                                        account unilaterally.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.6</span>The site administration has the right to refuse to withdraw
                                        funds from the account if the User fails or improperly fulfills the rules for
                                        using the site set forth in this offer.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>4.</span> Rules for using the site for Clients (Customers):
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>4.1</span>Type and specificity of the Information materials posted through
                                        the socialbooster.me Service on third-party Internet resources, the moment the
                                        placement starts, as well as other conditions regarding the placement of
                                        Information materials, are determined by the Client through the user interface
                                        of the socialbooster.me service.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.2</span>The cost of the services provided cannot be less than the base
                                        cost published on the socialbooster.me website at: {{ URL::to('/') }}.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.3</span>Maintaining statistics on the placement of information materials
                                        and providing the Client with access to it is carried out by the Site
                                        Administration. The collected statistical information is posted on the
                                        socialbooster.me secure Internet resource.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.4</span>The client gets access to the Internet resource
                                        "socialbooster.me" and the statistical information posted on it with the help of
                                        independently determined unique login and password.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.5</span>The client independently monitors the change in the details of
                                        the Contractor and is responsible for the correctness of the payments made by
                                        him to the Contractor.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.6</span>
                                        The customer undertakes:
                                        <ol class="list-circle">
                                            <li>Timely and in full to pay for the services rendered by the Contractor.
                                                Information materials posted by the Client must not contain references
                                                or references to goods and services that contradict the laws of the
                                                Russian Federation and the Advertising Law.
                                            </li>
                                            <li>To provide for posting on the Internet resources of third parties
                                                informational materials belonging to him by right of ownership, to which
                                                he has exclusive rights or another right determined by the current
                                                legislation of the Russian Federation.
                                            </li>
                                            <li>Within three days, at the request of the Site Administration, provide
                                                written confirmation of the rights to posted information materials.
                                            </li>
                                            <li>If the Client places information on goods or services for which there
                                                are rules and restrictions, then he is obliged to have all the necessary
                                                permits, licenses or certificates and provide copies of them within
                                                three days at the request of the Site Administration.
                                            </li>
                                            <li>Independently get acquainted with the official information about the
                                                service service "socialbooster.me", published at:
                                                {{ URL::to('/') }}.
                                            </li>
                                            <li>Independently ensure the availability on the Internet of their pages
                                                indicated in the links of ads. The page of the Client’s site, which is
                                                referred to by the Client’s information materials, should correctly open
                                                in any of the popular Internet user browsers (Internet Explorer, Mozilla
                                                Firefox, Opera, Safari), should not contain malware, and should not open
                                                more than one pop-up window.
                                            </li>
                                            <li>To guarantee the absence of claims, sanctions and other forms of
                                                proceedings for the placement of information materials (advertising) for
                                                their pages, both before and after their execution.
                                            </li>
                                            <li>To guarantee the absence of claims, sanctions and other forms of
                                                proceedings for paying for the placement of information materials
                                                (advertising) for their pages, both before and after their execution.
                                            </li>
                                        </ol>
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>5.</span> Rules for registration and cancellation of subscriptions::
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>5.1</span>
                                        Subscription allows the User to make purchases of services by regular automatic
                                        transfers of funds from his payment resources on the basis of advance consent.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.2</span>
                                        By acquiring an automatic renewal service, User agrees to subsequent automatic
                                        debit to pay for this service in subsequent periods in the amount of the cost of
                                        the service on the moment of making such payments.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.3</span>
                                        The estimated subscription period is 30 calendar days from the date of its
                                        successful clearance.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.4</span>
                                        By default, the subscription will be renewed automatically at the end of the
                                        settlement period. If, during an automatic renewal, the payment instrument is
                                        rejected in processing center, 3 more attempts will be made within 3 days.
                                        During of this period, the subscription services of the account will not be
                                        updated until the payment is successfully decommissioned.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.5</span>
                                        At any time during the subscription, the User can change the available for
                                        changes the subscription parameters, however the changed parameters will be
                                        applied in beginning of a new billing period.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.6</span>
                                        The user can unsubscribe at any time in the personal account subscriptions at:
                                        {{ URL::to('/subscriptions') }}. But actually Subscription will terminate
                                        at the beginning of the next billing period.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.7</span>
                                        Subscription payments are not refundable. All resources available for purchase
                                        subscriptions remain with the User until the end of the subscription, even if it
                                        is canceled.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>6.</span> Final Provisions:
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>6.1</span>The provisions of the offer are regulated and interpreted in
                                        accordance with the legislation of the Russian Federation. Issues not regulated
                                        by this offer shall be resolved in accordance with the legislation of the
                                        Russian Federation.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.2</span>In the event of any disputes or disagreements related to the
                                        execution of this offer, the User and the Site Administration will make every
                                        effort to resolve them through negotiations between them. In the event that
                                        disputes are not resolved through negotiations, disputes shall be resolved in
                                        accordance with the applicable laws of the Russian Federation.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.3</span>This offer comes into force for the User from the moment of its
                                        acceptance in accordance with clause 2.2 of this offer and is valid for an
                                        indefinite period.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.4</span>If, for one reason or another, one or more of the provisions of
                                        this offer is declared invalid or not legally binding, this does not affect the
                                        validity or applicability of the remaining provisions.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.5</span>Guarantees for the absence of sanctions to the results of work
                                        by the administration of third-party resources in case of non-compliance with
                                        the above norms and requirements The site administration does not give.
                                        <ol class="list-circle">
                                            <li>The service does not guarantee the effectiveness of orders, if consumers
                                                are not interested in the customer’s data.
                                            </li>
                                            <li>The administration of socialbooster.me has the right to refuse to
                                                provide services without announcing the reason (also in case of
                                                violation by the customer of the rules and norms of this agreement)
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.6</span>Funds paid for the provision of services on orders, after
                                        performing these services are not refundable.
                                        <ol class="list-circle">
                                            <li>The order execution method and consumers are individuals or legal
                                                entities involved in these orders through advertising, inviting,
                                                arbitration and offers, the platforms of which are owned by the Service
                                                or partners of this Service.
                                            </li>
                                            <li>The amount of refund to the sender in case of refusal to use this
                                                service can be no more than 10% of the payment amount. (Or 60% for
                                                orders of more than $ 1000 and 70% for violation by the customer of the
                                                rules and regulations of the User Agreement)
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.7</span>Payment is made in cash in the currency provided for when
                                        creating the transaction for the details provided during the creation of the
                                        transaction. If the parties to the transaction are residents of the Russian
                                        Federation, payment is made in Russian rubles at the exchange rate of the
                                        Central Bank of the Russian Federation.
                                        <ol class="list-circle">
                                            <li>The agreement terminates:
                                                <ol class="list-square">
                                                    <li>on the initiative of the Service Administration in case of
                                                        violation by the User of the terms of the Agreement and / or
                                                        Agreement concluded under the terms of this Offer.
                                                    </li>
                                                    <li>at the initiative of the User upon termination of the Use of the
                                                        Service;
                                                    </li>
                                                </ol>
                                            </li>
                                            <li>If the User intends to terminate the Agreement on his own initiative, or
                                                if the User receives a notification from the Service Administration
                                                about the termination of the Agreement, the User is obliged to stop
                                                using the service immediately.
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.8</span>User guarantees that all the conditions of the Agreement are
                                        clear to him, and the User accepts the conditions without reservation and in
                                        full.
                                        <ul class="list-circle">
                                            <li>By paying for services and registering on the socialbooster.me website,
                                                the Customer agrees to all of the above conditions and accepts (accepts)
                                                this user agreement, the offer to provide services.
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <p class="public-offer__text">Администрация сайта "socialbooster.me" предлагает Вам услуги
                                (сервисы) сайта, расположенного в сети Интернет по адресу: {{ URL::to('/') }}
                                (далее – сайт), на условиях, являющихся правилами пользования сайтом "socialbooster.me".
                                Настоящие правила рассматриваются Администрацией сайта как публичная оферта в
                                соответствии со ст. 437 Гражданского кодекса Российской Федерации.</p>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>1.</span> Термины и определения
                                </div>
                                <p class="public-offer__title-desc">Для целей настоящей оферты нижеприведенные термины и
                                    определения толкуются следующим образом:</p>

                                <ul>
                                    <li class="public-offer__text">
                                        <span>1.1</span>
                                        "Интернет-ресурс" - совокупность интегрированных программно-аппаратных средств и
                                        информации, предназначенной для публикации в сети Интернет и отображаемой в
                                        определенной текстовой, графической или звуковой формах. Интернет-ресурс
                                        доступен для пользователей сети Интернет посредством доменного имени и URL
                                        (Uniform Resource Locator) - уникального электронного адреса, позволяющих
                                        осуществлять доступ к информации и программно-аппаратному комплексу.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.2</span>
                                        "Интернет-страница" - страница (HTML-документ) Интернет-ресурса.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.3</span>
                                        "Информационные материалы" - любые текстовые, графические, аудио, видео и
                                        смешанные материалы информационного характера
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.4</span>
                                        "Размещение Информационных материалов" - техническое размещение информационных
                                        материалов клиента на Интернет-ресурсах.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.5</span>
                                        "Сервис "socialbooster.me" - программно-аппаратный комплекс, управляемый
                                        Администрацией сайта, предназначенный для выполнения взаимных обязательств
                                        сторонами.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.6</span>
                                        "Пользовательский интерфейс сервиса "socialbooster.me" - интерфейс доступа к
                                        статистике и управлению размещениями информационных материалов. Вход
                                        осуществляется с применением логина (e-mail) и пароля по адресу: <a
                                                href="{{ URL::to('/') }}" class="link" target="_blank">{{ URL::to('/') }}</a>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.7</span>
                                        "Пользователь" - любое лицо, зарегистрировавшееся на сайте
                                        {{ URL::to('/') }} и согласившееся с условиями пользования сайтом.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.8</span>
                                        "Клиент" ("Заказчик") - физическое или юридическое лицо, размещающее
                                        информационные материалы, принадлежащие ему на праве собственности или ином
                                        праве, определенном действующим законодательством РФ, в сети Интернет
                                        посредством сервиса "<strong>socialbooster.me</strong>"
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.9</span>
                                        "Исполнитель" – физическое лицо, достигшее 18 лет, дееспособное, являющееся
                                        пользователем сети Интернет, исполняющее обязанности по размещению
                                        информационных материалов Клиента в собственных блогах, страницах веб-сайтов и
                                        иных интернет-ресурсах.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.10</span>
                                        "Бан" – временное ограничение работы пользователя на определенный или
                                        неопределенный срок по решению Администрации сайта.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>1.11</span>
                                        Подписка (рекуррентный платеж) – вид платежа, подразумевающий автоматическое
                                        списание средств за получаемые услуги с платежных ресурсов Пользователя без
                                        необходимости каких-либо повторных действий с его стороны.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>2.</span> Общие положения
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>2.1</span>
                                        Использование материалов и сервисов Сайта регулируется нормами действующего
                                        законодательства Российской Федерации.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.2</span>
                                        Акцепт настоящей оферты производится путем последовательного совершения
                                        следующих действий:
                                        <ol class="list-circle">
                                            <li>
                                                ознакомление с условиями настоящей оферты и Правилами пользования
                                                сервисом "socialbooster.me", размещенными на сайте
                                                {{ URL::to('/') }};
                                            </li>
                                            <li>
                                                заполнение анкеты, опубликованной в виде регистрационной формы;
                                            </li>
                                            <li>
                                                самостоятельное определение используемых далее логина (e-mail) и пароля
                                                длиной не менее пяти символов, включающего цифры, заглавные и прописные
                                                буквы латинского алфавита;
                                            </li>
                                            <li>
                                                отправка регистрационной формы через интерфейс на сайте
                                                {{ URL::to('/') }}.
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.3</span>
                                        Пользователь несет полную ответственность за сохранность своих логина и пароля в
                                        сервисе "socialbooster.me" и за убытки, которые могут возникнуть по причине
                                        несанкционированного использования его логина, пароля и/или канала доступа.
                                        Администрация сайта не несет ответственности и не возмещает убытки, возникшие по
                                        причине несанкционированного доступа третьих лиц к информации о счете
                                        Пользователя.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.4</span>
                                        Пользователь не вправе передавать третьем лицам логин и пароль, позволяющие
                                        получать доступ к пользовательскому интерфейсу сервиса "socialbooster.me" и иным
                                        сервисам, предоставляемым Администрацией сайта. Все действия, требующие
                                        использования логина и пароля, считаются совершенными Пользователем.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.5</span>
                                        Администрация сайта вправе в любое время в одностороннем порядке изменять
                                        условия настоящей оферты. Такие изменения вступают в силу по истечении 3 (трех)
                                        дней с момента размещения новой версии оферты на сайте. При несогласии
                                        Пользователя с внесенными изменениями он обязан отказаться от доступа к сайту,
                                        прекратить использование материалов и сервисов сайта.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.6</span>
                                        Использование материалов сайта без согласия правообладателей не допускается (ст.
                                        1270 ГК РФ). При цитировании материалов сайта ссылка на сайт обязательна (п. 1
                                        ст. 1274 ГК РФ).
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.7</span>
                                        Комментарии и иные записи Пользователя на сайте не должны вступать в
                                        противоречие с требованиями законодательства Российской Федерации и общепринятых
                                        норм морали и нравственности.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.8</span>
                                        Пользователь согласен с тем, что Администрация Сайта не несет ответственности и
                                        не имеет прямых или косвенных обязательств перед Пользователем в связи с любыми
                                        возможными или возникшими потерями или убытками, связанными с любым содержанием
                                        сайта, регистрацией авторских прав и сведениями о такой регистрации, товарами
                                        или услугами, доступными на сайте или полученными через внешние сайты или
                                        ресурсы либо иные контакты пользователя, в которые он вступил, используя
                                        размещенную на сайте информацию или ссылки на внешние ресурсы.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.9</span>
                                        Пользователь принимает положение о том, что все материалы и сервисы сайта или
                                        любая их часть могут сопровождаться рекламой. Пользователь согласен с тем, что
                                        Администрация сайта не несет какой-либо ответственности и не имеет каких-либо
                                        обязательств в связи с такой рекламой.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.10</span>
                                        Администрация сайта не несет ответственности перед третьими лицами за содержание
                                        информации, используемой в размещаемых Клиентом информационных материалах, а
                                        также за имущественный, моральный или какой-либо иной ущерб, причиненный в
                                        результате использования третьими лицами указанной информации.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.11</span>
                                        Стоимость размещения информационных материалов регулируется Администрацией сайта
                                        в одностороннем порядке и в любой момент может быть изменена.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.12</span>
                                        Стоимость размещения информационного материала также напрямую зависит от
                                        стоимости, выставленной самим Пользователем сервиса {{ URL::to('/') }}.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.13</span>
                                        Администрация сайта оставляет за собой право отклонить любой информационный
                                        материал без объяснения причин.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.14</span>
                                        Администрация сайта имеет право заблокировать аккаунт Пользователя в случае,
                                        если будут зафиксированы попытки маскировки реального IP-адреса Пользователя
                                        (например, использование сети прокси-серверов, сервиса TOR или аналогов).
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.15</span>
                                        Пользователю при использовании Сайта запрещается:
                                        <ol class="list-circle">
                                            <li>регистрироваться в качестве Пользователя от имени или вместо другого
                                                лица ("фальшивый аккаунт");
                                            </li>
                                            <li>вводить Пользователей в заблуждение относительно своей личности,
                                                используя логин и пароль другого зарегистрированного Пользователя;
                                            </li>
                                            <li>
                                                загружать, хранить, публиковать, распространять и предоставлять доступ
                                                или иным образом использовать любую информацию, которая:
                                                <ol class="list-square">

                                                    <li>
                                                        Содержит угрозы, дискредитирует, оскорбляет, порочит честь и
                                                        достоинство или деловую репутацию или нарушает
                                                        неприкосновенность частной жизни других Пользователей или
                                                        третьих лиц, нарушает права несовершеннолетних лиц;
                                                    </li>
                                                    <li>
                                                        Является вульгарной или непристойной, содержит нецензурную
                                                        лексику, содержит порнографические изображения и тексты или
                                                        сцены сексуального характера с участием несовершеннолетних,
                                                        содержит сцены насилия, либо бесчеловечного обращения с
                                                        животными;
                                                    </li>
                                                    <li>
                                                        Содержит описание средств и способов суицида, любое
                                                        подстрекательство к его совершению;
                                                    </li>
                                                    <li>
                                                        Пропагандирует и/или способствует разжиганию расовой,
                                                        религиозной, этнической ненависти или вражды, пропагандирует
                                                        фашизм или идеологию расового превосходства;
                                                    </li>
                                                    <li>
                                                        Содержит экстремистские материалы; пропагандирует преступную
                                                        деятельность или содержит советы, инструкции или руководства по
                                                        совершению преступных действий;
                                                    </li>
                                                    <li>
                                                        Содержит информацию ограниченного доступа, включая, но не
                                                        ограничиваясь, государственной и коммерческой тайной,
                                                        информацией о частной жизни третьих лиц;
                                                    </li>
                                                    <li>
                                                        Носит мошеннический характер;
                                                    </li>
                                                    <li>
                                                        А также нарушает иные права и интересы граждан и юридических лиц
                                                        или требования законодательства Российской Федерации.
                                                    </li>
                                                </ol>
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>2.16</span>
                                        Все вопросы или проблемы, связанные с использованием сервиса "socialbooster.me",
                                        решаются в группах поддержки или в форме обратной связи. Администрация сайта
                                        рассматривает заявки по мере их поступления.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>3.</span> Правила пользования сайтом для Исполнителей:
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>3.1</span>
                                        Под выполненным заданием понимается размещение Исполнителем информационного
                                        материала на своей странице, в блоге, других интернет-ресурсах на постоянный
                                        срок (до удаления аккаунта из системы) или выполнение определенного действия в
                                        сети Интернет по заданным критериям.
                                        <p><b>Размещение информационного материала Исполнитель осуществляет на свой
                                                страх и риск. Администрация сайта не несет никакой ответственности за
                                                размещение материала Пользователем сервиса.</b>
                                        </p>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.2</span>
                                        Пользователи, чьи страницы, блоги, другие интернет-ресурсы, на которых
                                        планируется размещение информационных материалов Клиента, целиком состоят из
                                        рекламных материалов, заблокированы, наполняются автоматически, не имеют
                                        активных посетителей (фолловеров) или малое их количество, а также являются
                                        фальшивыми (мультиводство) не допускаются для использования сервиса
                                        "socialbooster.me".
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.3</span>
                                        Денежное вознаграждение и премии полученные Пользователем, когда Исполнитель
                                        является Клиентом (Заказчиком) по другому заданию или заказу, может быть
                                        перечислено внутренним переводом на счет Клиента (Заказчика) на сайте.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.4</span>
                                        Комиссия при выводе денежных средств со счета на сайте может достигать 30%,
                                        вывод осуществляется в течение 10 рабочих дней с момента подачи заявки
                                        Пользователем и ее подтверждением Администрацией сайта.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.5</span>
                                        Администрация сайта вправе изменять процент (%) комиссии на пополнение
                                        внутреннего счёта Пользователя и процент (%) комиссии на вывод денежных средств
                                        с внутреннего счёта Пользователя в одностороннем порядке.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>3.6</span>
                                        Администрация сайта вправе отказать в выводе денежных средств со счета при
                                        неисполнении или ненадлежащем исполнении Пользователем изложенных в настоящей
                                        оферте правил пользования сайтом.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>4.</span> Правила пользования сайтом для Клиентов (Заказчиков):
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>4.1</span>
                                        Тип и специфика Информационных материалов, размещаемых через Сервис
                                        "socialbooster.me" на интернет-ресурсах третьих лиц, момент начала размещения, а
                                        также иные условия, касающиеся размещения Информационных материалов,
                                        определяются Клиентом посредством пользовательского интерфейса сервиса
                                        "socialbooster.me".
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.2</span>
                                        Стоимость оказываемых услуг не может быть меньше базовой стоимости,
                                        опубликованной на сайте "socialbooster.me" по адресу: {{ URL::to('/') }}.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.3</span>
                                        Ведение статистики размещения информационных материалов и предоставление Клиенту
                                        доступа к ней осуществляется Администрацией сайта. Собранная статистическая
                                        информация размещается на защищенном интернет-ресурсе "socialbooster.me".
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.4</span>
                                        Клиент получает доступ к интернет-ресурсу "socialbooster.me" и размещенной на
                                        нем статистической информации с помощью самостоятельно определенных уникальных
                                        логина и пароля.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.5</span>
                                        Клиент самостоятельно отслеживает изменение реквизитов Исполнителя и несет
                                        ответственность за правильность производимых им платежей Исполнителю.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>4.6</span>
                                        Клиент обязуется:
                                        <ol class="list-circle">
                                            <li>
                                                Своевременно и в полном объеме оплачивать оказанные Исполнителем услуги.
                                                Информационные материалы, размещаемые Клиентом, не должны содержать
                                                упоминания или ссылки на товары и услуги, противоречащие
                                                законодательству РФ и Закону о рекламе.
                                            </li>
                                            <li>
                                                Предоставлять для размещения на интернет-ресурсах третьих лиц
                                                информационные материалы, принадлежащие ему на праве собственности, на
                                                которые он имеет исключительные права или ином праве, определенном
                                                действующим законодательством РФ.
                                            </li>
                                            <li>
                                                В течение трех дней по требованию Администрации сайта предоставить
                                                письменное подтверждение прав на размещаемые информационные материалы.
                                            </li>
                                            <li>
                                                В случае если Клиент размещает информацию о товарах или услугах, для
                                                которых существуют правила и ограничения, то он обязан иметь все
                                                необходимые разрешительные документы, лицензии или сертификаты и
                                                предоставить их копии в течение трех дней по требованию Администрации
                                                сайта.
                                            </li>
                                            <li>
                                                Самостоятельно знакомиться с официальной информацией об обслуживании
                                                сервиса "socialbooster.me", опубликованной по адресу:
                                                {{ URL::to('/') }}.
                                            </li>
                                            <li>
                                                Самостоятельно обеспечивать доступность в сети Интернет своих страниц,
                                                указываемых в ссылках объявлений. Страница сайта Клиента, на которую
                                                ссылаются информационные материалы Клиента, должна корректно открываться
                                                в любом из популярных браузеров пользователя Интернет (Internet
                                                Explorer, Mozilla Firefox, Opera, Safari), не должна содержать
                                                вредоносных программ, а также не должна открывать более одного
                                                всплывающего окна.
                                            </li>
                                            <li>
                                                Гарантировать отсутствие претензий, санкций и иных форм разбирательств
                                                по размещению информационных материалов (рекламы) для своих страниц, как
                                                до, так и после их исполнения.
                                            </li>
                                            <li>
                                                Гарантировать отсутствие претензий, санкций и иных форм разбирательств
                                                по оплате размещения информационных материалов (рекламы) для своих
                                                страниц, как до, так и после их исполнения.
                                            </li>
                                        </ol>
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>5.</span> Правила оформления и отмены подписок:
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>5.1</span>
                                        Подписка позволяет Пользователю осуществлять покупки услуг сервиса путем
                                        регулярных без участия Пользователя автоматических переводов денежных средств с
                                        его платежных ресурсов на основании заранее данного согласия.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.2</span>
                                        Приобретая услугу сервиса, предусматривающую автоматическое продление,
                                        Пользователь дает свое согласие на последующие автоматические списания средств
                                        для оплаты данной услуги в последующих периодах в размере стоимости услуги на
                                        момент совершения таких платежей.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.3</span>
                                        Расчетный период подписки составляет 30 календарных дней с момента ее успешного
                                        оформления.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.4</span>
                                        По умолчанию подписка будет продлеваться автоматически в конце расчетного
                                        периода. Если при автоматическом продлении платежное средство будет отклонено в
                                        процессинговом центре, будут сделаны еще 3 попытки в течении 3-х дней. В течении
                                        этого периода услуги подписки аккаунта не будут обновлены, пока платеж не будет
                                        удачно списан.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.5</span>
                                        В любой момент действия подписки, Пользователь может изменить доступные для
                                        изменения параметры подписки, однако измененные параметры будут применены в
                                        начале нового расчетного периода.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.6</span>
                                        Пользователь может отменить подписку в любое время в личном кабинете управления
                                        подписками по адресу: {{ URL::to('/subscriptions') }}. Но фактически
                                        прекращение действия подписки произойдет в начале следующего расчетного периода.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>5.7</span>
                                        Платежи по подпискам не возвращаются. Все ресурсы, доступные для приобретенной
                                        подписки, остаются у Пользователя до окончания подписки, даже если она будет
                                        отменена.
                                    </li>
                                </ul>
                            </div>

                            <div class="public-offer__item">
                                <div class="public-offer__subtitle">
                                    <span>6.</span> Заключительные положения:
                                </div>
                                <ul>
                                    <li class="public-offer__text">
                                        <span>6.1</span>
                                        Положения оферты регулируются и толкуются в соответствии с законодательством
                                        Российской Федерации. Вопросы, не урегулированные настоящей офертой, подлежат
                                        разрешению в соответствии с законодательством Российской Федерации.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.2</span>
                                        В случае возникновения любых споров или разногласий, связанных с исполнением
                                        настоящей оферты, Пользователь и Администрация сайта приложат все усилия для их
                                        разрешения путем проведения переговоров между ними. В случае, если споры не
                                        будут разрешены путем переговоров, споры подлежат разрешению в порядке,
                                        установленном действующим законодательством Российской Федерации.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.3</span>
                                        Настоящая оферта вступает в силу для Пользователя с момента его акцепта в
                                        соответствии с п. 2.2 настоящей оферты и действует в течение неопределенного
                                        срока.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.4</span>
                                        Если по тем или иным причинам одно или несколько положений настоящей оферты
                                        будут признаны недействительными или не имеющими юридической силы, это не
                                        оказывает влияния на действительность или применимость остальных положений.
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.5</span>
                                        Гарантий на отсутствие санкций к результатам работы со стороны администрации
                                        сторонних ресурсов при несоблюдении вышеперечисленный норм и требований
                                        Администрация сайта не дает.
                                        <ol class="list-circle">
                                            <li>
                                                Сервис не дает гарантий на эффективность заказов, при
                                                незаинтересованности потребителей в данных заказчика.
                                            </li>
                                            <li>
                                                Администрация "socialbooster.me" имеет право отказать в предоставлении
                                                услуг без оглашения причины (так же при нарушении заказчиком правил и
                                                норм данного соглашения)
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.6</span>
                                        Денежные средства, оплаченные за оказание услуг по заказам, после выполнения
                                        данных услуг возврату не подлежат.
                                        <ol class="list-circle">
                                            <li>
                                                Способ исполнения заказа и потребители являются физическими или
                                                юридическими лицами привлеченными к данным заказам путем рекламы,
                                                инвайтинга, арбитража и офферов, владельцем площадок которых является
                                                Сервис либо партнеры данного Сервиса.
                                            </li>
                                            <li>
                                                Сумма возврата средств отправителю при отказе от использования данного
                                                сервиса может быть не больше 10% от суммы оплаты. (Либо 60% при заказах
                                                более чем на 1000$ и 70% при нарушении заказчиком правил и норм
                                                Пользовательского Соглашения)
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.7</span>
                                        Оплата осуществляется денежными средствами в валюте, предусмотренной при
                                        создании сделки по предоставленным при создании сделки реквизитам. В случае,
                                        если стороны сделки - резиденты РФ, оплата производится в рублях Рф по курсу ЦБ
                                        РФ.
                                        <ol class="list-circle">
                                            <li>
                                                Соглашение прекращает действие:
                                                <ol class="list-square">
                                                    <li>по инициативе Администрации Сервиса в случае нарушения
                                                        Пользователем условий Соглашения и/или Договора, заключенного на
                                                        условиях настоящей Оферты.
                                                    </li>
                                                    <li>по инициативе Пользователя при прекращении Использования
                                                        Сервиса;
                                                    </li>
                                                </ol>
                                            </li>
                                            <li>
                                                В случае намерения Пользователя прекратить действие Соглашения по своей
                                                инициативе, либо получения Пользователем уведомления от Администрации
                                                Сервиса о прекращении действия Соглашения, Пользователь обязан
                                                прекратить использование сервиса незамедлительно.
                                            </li>
                                        </ol>
                                    </li>
                                    <li class="public-offer__text">
                                        <span>6.8</span>
                                        Пользователь гарантирует, что все условия Договора ему понятны, и Пользователь
                                        принимает условия без оговорок и в полном объеме.
                                        <ol class="list-circle">
                                            <li>Оплачивая услуги и регистрируясь на сайте "socialbooster.me" Заказчик
                                                соглашается со всеми вышеперечисленными условиями и принимает
                                                (Акцептует) данное пользовательское соглашение, оферту на оказание
                                                услуг.
                                            </li>
                                        </ol>
                                    </li>
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
