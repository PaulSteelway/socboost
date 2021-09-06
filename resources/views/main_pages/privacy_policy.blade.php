@extends('layouts.customer')

@section('title', __('Privacy policy') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        {{--privacy policy--}}
        <section class="privacy-policy">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="privacy-policy__block">
                            <div class="privacy-policy__title">{{__('Privacy policy')}}</div>
                            <div class="privacy-policy__img">
                                <img src="/images/privacy-policy.png" alt="">
                            </div>
                            <div class="privacy-policy__text">
                                {{ __('This "Privacy Policy" (hereinafter referred to as the "Policy") is a policy on how socialbooster.me (hereinafter referred to as "we" and/or "Administration") uses the data of Internet users (hereinafter referred to as "you" and/or "User") collected using the socialbooster.me website (hereinafter referred to as the "Site").') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="privacy-policy-text">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="privacy-policy__item">
                            <div class="privacy-policy__subtitle">
                                <span>1.</span> {{__('Processed data')}}
                            </div>
                            <ul>
                                <li class="privacy-policy__text">
                                    <span>1.1</span> {{__('We do not collect your personal data using the Site.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.2</span> {{__('All data collected on the Site is provided and accepted in an impersonal form ("Impersonal Data").')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.3</span> {{__('Anonymized Data includes the following information, which does not allow you to be identified:')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.3.1</span> {{__('Information you provide about yourself using the Site\'s online forms and software modules, including your name and telephone number and/or email address.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.3.2</span> {{__('Data, which is transmitted in an impersonal form automatically, depending on the settings of the software you use.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.4</span> {{__('The Administration has the right to establish requirements for the composition of the User\'s Impersonal Data, which is collected using the Website.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.5</span> {{__('If certain information is not marked as mandatory, its provision or disclosure is at the discretion of the User. At the same time, you give your informed consent for access to such data by an unlimited number of people. Such data becomes publicly available at the moment it is provided and/or otherwise disclosed.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.6</span> {{__('Administration does not check the authenticity of provided data and the availability of User\'s necessary consent to its processing in accordance with this Policy, assuming that the User acts in good faith, with circumspection, and makes all necessary efforts to keep such information up to date and to obtain all necessary consents for its use.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.7</span> {{__('You understand and accept the possibility of third parties\' software being used on the Site, as a result of which such parties may receive and transmit the data specified in clause 1.3 in an impersonal form.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.8</span> {{__('The composition and terms of collecting impersonal data using third party software are determined directly by their copyright holders and may include')}}
                                    <ol class="list-circle">
                                        <li>{{ __('browser data (type, version, cookie);') }}</li>
                                        <li>{{ __('device data and its placement;') }}</li>
                                        <li>{{ __('operating system data (type, version, screen resolution);') }}</li>
                                        <li>{{ __('query data (time, transfer source, IP-address).') }}</li>
                                    </ol>
                                </li>
                                <li class="privacy-policy__text">
                                    <span>1.9</span> {{__('The Administration is not responsible for the way the User\'s Impersonal Data is used by third parties.')}}
                                </li>
                            </ul>
                        </div>

                        <div class="privacy-policy__item">
                            <div class="privacy-policy__subtitle">
                                <span>2.</span> {{__('Purposes of data processing')}}
                            </div>
                            <ul>
                                <li class="privacy-policy__text">
                                    <span>2.1.</span> {{__('The Administration shall use data for the following purposes:')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>2.1.1.</span> {{__('Processing of incoming requests and communication with the User;')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>2.1.2.</span> {{__('Information services, including sending promotional and information materials;')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>2.1.3.</span> {{__('Marketing, statistical and other research;')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>2.1.4.</span> {{__('Targeting of advertising materials on the Site.')}}
                                </li>
                            </ul>
                        </div>

                        <div class="privacy-policy__item">
                            <div class="privacy-policy__subtitle">
                                <span>3.</span> {{__('Data protection requirements')}}
                            </div>
                            <ul>
                                <li class="privacy-policy__text">
                                    <span>3.1.</span> {{__('Administration stores data and protects it from unauthorized access and dissemination in accordance with its internal rules and regulations.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>3.2.</span> {{__('The received data shall be kept confidential, except when it is made publicly available by the User and when the technology and software of third parties used on the Website, or the settings of the software used by the User provide for an open exchange of data with persons and/or other participants and users of the Internet.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>3.3.</span> {{__('In order to improve the quality of its work, the Administration has the right to keep log files on the actions performed by the User within the framework of use of the Website for 1 (One) year.')}}
                                </li>
                            </ul>
                        </div>

                        <div class="privacy-policy__item">
                            <div class="privacy-policy__subtitle">
                                <span>4.</span> {{__('Data transfer')}}
                            </div>
                            <ul>
                                <li class="privacy-policy__text">
                                    <span>4.1.</span> {{__('Administration has the right to transfer data to third parties in the following cases:')}}

                                    <ol class="list-circle">
                                        <li>{{ __('The User has expressed its consent to such actions, including the cases when the User uses the settings of the software used, not limiting the provision of certain information;') }}</li>
                                        <li>{{ __('The transfer is required as part of the User\'s use of the functionality of the Site;') }}</li>
                                        <li>{{ __('The transfer is required in accordance with the purposes of data processing;') }}</li>
                                        <li>{{ __('In connection with the transfer of the Site to the possession, use or ownership of such third party;') }}</li>
                                        <li>{{ __('At the request of a court or other authorized government agency within the statutory procedure;') }}</li>
                                        <li>{{ __('To protect the rights and legitimate interests of the Administration in connection with violations committed by the User.') }}</li>
                                    </ol>
                                </li>
                            </ul>
                        </div>

                        <div class="privacy-policy__item">
                            <div class="privacy-policy__subtitle">
                                <span>5.</span> {{__('Change of Privacy Policy')}}
                            </div>
                            <ul>
                                <li class="privacy-policy__text">
                                    <span>5.1.</span> {{__('This Policy may be amended or terminated by Administration unilaterally without prior notification to User. The new version of the Policy shall be effective from the moment of its posting on the Website, unless otherwise stipulated by the new version of the Policy.')}}
                                </li>
                                <li class="privacy-policy__text">
                                    <span>5.2.</span> {{__('The current version of the Policy is available on the Website at the address.')}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
