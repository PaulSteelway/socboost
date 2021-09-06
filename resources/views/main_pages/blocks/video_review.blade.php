@if(isset($reviews))
    <section class="reviews reviews--acc">
        <h2 class="reviews--acc__title">{{__('Customer reviews')}}</h2>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="reviews__list reviews__list--video">
                        @foreach($reviews as $review)
                            <div class="review-post__container">
                                <div class="review-post__video">
                                    <iframe class="embed-responsive-item" width="330" height="199"
                                            src="{{$review->video}}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="review-post">
                                    <div class="review-post__author">{{$review->user->name}}</div>
                                    <div class="review-post__date">{{$review->created_at->format('d-m-Y')}}</div>
                                    <div class="review-post__text">{{$review->text}}.</div>
                                </div>
                            </div>
                        @endforeach
                        {{ $reviews->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
