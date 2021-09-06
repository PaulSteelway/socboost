<section class="feedbacks">
  <div class="container">
    <h2 class="feedbacks__title">
      {{__('Reviews about the system')}}
    </h2>

    {{-- <div class="feedbacks__subtitle">{{__('Recommendations of our users')}}</div> --}}

    <div class="row">

      <div class="col-12 col-lg-7">
        <div class="feedbacks__social">
          <div class="feedbacks__social-title">
            {{__('Media writes about us')}}
          </div>
          <p class="feedbacks__social-text">
            {{__('On our site you can learn the feedback of our clients and the opinions of SMM specialists who used the system to create an advertising campaign to promote social networks. Here are links with reviews about our platform to promote')}}.
          </p>
        </div>
      </div>

      <div class="col-12 col-lg-5">
        <div class="feedbacks__blocks">

          <a href="/reviews" class="feedbacks__block">
            <div class="feedbacks__block-left">
              <div class="feedbacks__block-icon">
                <div class="sm-reviews"></div>
                <!-- <img src="{{ asset('svg/reviews.svg') }}" alt="Reviews"> -->
              </div>
              <div class="feedbacks__block-name">{{__('Our customer reviews')}}</div>
            </div>
            <div class="feedbacks__link-icon">
              <img src="{{ asset('svg/arrow-go.svg') }}" alt="Read more">
            </div>
          </a>

          <a href="/video-review" class="feedbacks__block">
            <div class="feedbacks__block-left">
              <div class="feedbacks__block-icon">
                <div class="sm-reviews"></div>
                <!-- <img src="{{ asset('svg/reviews.svg') }}" alt="Reviews"> -->
              </div>
              <div class="feedbacks__block-name">{{__('Video reviews from our customers')}}</div>
            </div>
            <div class="feedbacks__link-icon">
              <img src="{{ asset('svg/arrow-go.svg') }}" alt="Read more">
            </div>
          </a>

          <a href="https://vk.com/topic-194891693_41492617" class="feedbacks__block" target="_blank">
            <div class="feedbacks__block-left">
              <div class="feedbacks__block-icon">
                <div class="sm-reviews"></div>
                <!-- <img src="{{ asset('svg/reviews.svg') }}" alt="Reviews"> -->
              </div>
              <div class="feedbacks__block-name">{{__('Reviews about us on')}} {{__('VK')}}</div>
            </div>
            <div class="feedbacks__link-icon">
              <img src="{{ asset('svg/arrow-go.svg') }}" alt="Read more">
            </div>
          </a>

          <a href="https://www.facebook.com/pg/socialbooster.me/reviews" class="feedbacks__block" target="_blank">
            <div class="feedbacks__block-left">
              <div class="feedbacks__block-icon">
                <div class="sm-reviews"></div>
                <!-- <img src="{{ asset('svg/reviews.svg') }}" alt="Reviews"> -->
              </div>
              <div class="feedbacks__block-name">{{__('Reviews about us on')}} {{__('Facebook')}}</div>
            </div>
            <div class="feedbacks__link-icon">
              <img src="{{ asset('svg/arrow-go.svg') }}" alt="Read more">
            </div>
          </a>

          <a href="https://sites.reviews/otzyvy/site/socialbooster.me/?id=778065" class="feedbacks__block" target="_blank">
            <div class="feedbacks__block-left">
              <div class="feedbacks__block-icon">
                <div class="sm-reviews"></div>
                <!-- <img src="{{ asset('svg/reviews.svg') }}" alt="Reviews"> -->
              </div>
              <div class="feedbacks__block-name">{{__('Reviews about us on')}} {{__('Sites.Reviews')}}</div>
            </div>
            <div class="feedbacks__link-icon">
              <img src="{{ asset('svg/arrow-go.svg') }}" alt="Read more">
            </div>
          </a>

        </div>
      </div>
    </div>
  </div>
</section>
