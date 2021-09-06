	<div class="row">
       <div>
           <header>
               <h2>{{ __("High-quality promotion") }} <span>{{ __("in social networks") }}

               <br />{{ __("at the lowest prices in the world") }}</span></h2>
               {{ __("From") }} <strong>{{ __("1 cent") }}</strong> {{ __("to a") }} <strong>{{ __("subscriber on Instagram") }}</strong> {{ __("and from") }} <strong>{{ __("1$") }}</strong> {{ __("for") }} <strong>{{ __("1000 views") }}</strong> {{ __("on YouTube") }} <br />
               <div class="promo-image"></div>
               <h5><!-- У нас вы можете воспользоваться услугами по раскрутке: --><span><strong>{{ __("We work with:") }}</strong> {{ __("ВКонтакте, Instagram, Telegram, YouTube, SoundCloud, FaceBook, TikTok.") }}</span></h5>
           </header>
           <div class="content home">
               <div class="step-1">
                   <div class="unit">
                       <p>{{ __("Nowadays, social networks are not just a place for online communication, but an excellent opportunity for self-expression and income generation. However, sometimes in order to get a significant result in the promotion, own forces are not enough.") }}<br /> <strong>{{ __("In this case, it is worth turning to professionals!") }} </strong></p>

                   </div></div>

               <div class="step-2">
                   <div class="name"><img src="/img/birns.png" alt="" /><span>PRO-SMM.BIZ </span><strong>{{ __("THIS IS WHAT YOU NEED") }}</strong> {{ __("For those who want to gain") }} <em>{{ __("popularity in:") }}</em></div>
                   <ul>
                       <li class="vk"><span>{{ __("VK") }}</span></li>

                       <li class="instagram"><span>Instagram</span></li>
                       <li class="youtube"><span>YouTube</span></li>
                       <li class="facebook"><span>Facebook</span></li>


                   </ul>
               </div>
               <div class="step-3">
                   <p>{{ __("Getting started with the site is not difficult at all.") }} {{ __("First of all, you need") }} <strong>{{ __("to sign up and top up") }}</strong> {{ __("your personal account with an amount sufficient to pay for the desired services.") }} {{ __("The execution of a service starts automatically after you have paid for the order. All stages of execution can be") }} <strong>{{ __("tracked online") }}</strong> {{ __("at") }} <strong>{{ __("any time.") }}</strong> {{ __("Due to the fact that in most cases,") }} <strong>{{ __("instead of bots, people work") }}</strong>{{ __("- the so-called offers, the quality of the services provided is excellent, which means that you can get the desired result in the shortest possible time.") }}</p>
                   <p><strong>{{ __("We value our customers") }} {{ __("and aim at long-term cooperation with each of them. That is why we try to keep the quality at") }}</strong> <strong>{{ __("highest level,") }}</strong> {{ __("and also our prices are the lowest in the US and Europe.") }}
               </div>

           </div>
           @if(!\Auth::user())
           <div class="step-2">
               <a href="{{ route('login') }}" class="home-button">{{ __("Login") }} </a><span class="register-home"><a href="{{ route('register') }}">{{ __("Registration") }} </a></span>
           </div>
           @endif
       </div>
   </div>
   <div class="border">
       <span></span>
       <span></span>
       <span></span>
       <span></span>
       <span></span>
       <span></span>
       <span></span>
   </div>
