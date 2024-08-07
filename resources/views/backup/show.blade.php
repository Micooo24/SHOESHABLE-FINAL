
@extends('layouts.master')
@section('content')
@php
    $reviews = $product->reviews;
    $totalRatings = $reviews->count();
    $totalSum = $reviews->sum('ratings');
    $totalStars = $totalRatings * 5;
    $percentage = $totalStars > 0 ? round(($totalSum / $totalStars) * 100) : 0;
    if ($totalRatings > 0) {
        if ($percentage >= 80) {
            $stars = 5;
        } elseif ($percentage >= 60) {
            $stars = 4;
        } elseif ($percentage >= 40) {
            $stars = 3;
        } elseif ($percentage >= 20) {
            $stars = 2;
        } else {
            $stars = 1;
        }
    } else {
        $stars = 0;
    }
@endphp
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-3">
            <div class="card fixed-sidebar mt-3">
                <div class="card-body">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-body">
                    <h2>{{ $product->name }}</h2>
                    <p class="text-muted"><strong>Brand:</strong> {{ $product->brand->brand_name }}</p>
                    <div id="carousel-{{ $product->id }}" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach(explode(',', $product->img_path) as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset(trim($image)) }}" class="d-block w-100" style="height: 250px; object-fit: contain;" alt="Product Image">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carousel-{{ $product->id }}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-{{ $product->id }}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="star-rating display-only mt-3" style="justify-content: center;">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star-overall-{{ $i }}" name="rating-overall" value="{{ $i }}" {{ $i <= $stars ? 'checked' : '' }} disabled>
                            <label for="star-overall-{{ $i }}" class="fa fa-star {{ $i <= $stars ? 'checked' : '' }}"></label>
                        @endfor
                    </div>
                    <p>Overall Rating: <strong>{{ $percentage }}%</strong></p>
                    <h4 class="text-primary">Php {{ $product->cost }}</h4>
                    <p>{{ $product->description }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h3>Reviews:</h3>
                    <ul class="list-group">
                        @if($canReview)
                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addReviewModal">Add a Review</button>
                        @else
                            <p class="text-muted mt-3 mb-3">You can only write a review if you have ordered this product.</p>
                        @endif
                        @forelse ($product->reviews as $review)
                            <li class="list-group-item">
                                <div class="d-flex flex-column">
                                    <div class="review-images mb-2" style="display: flex; flex-wrap: wrap;">
                                        @if ($review->img_path)
                                            @foreach (explode(',', $review->img_path) as $img)
                                                <img src="{{ asset(trim($img)) }}" class="img-thumbnail mr-2" alt="Review Image" style="height: 100px; width: 100px; object-fit: cover;">
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="mb-1">Name: <strong class="h4">{{ $review->customer->username }}</strong></p>
                                    <div class="mb-2">
                                        <strong>Comment:</strong>
                                        <p>{{ $review->comments }}</p>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <strong class="mr-2">Rating:</strong>
                                        <div class="star-rating display-only" style="display: flex;">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star-{{ $review->id }}-{{ $i }}" name="rating-{{ $review->id }}" value="{{ $i }}" {{ $review->ratings == $i ? 'checked' : '' }} disabled>
                                                <label for="star-{{ $review->id }}-{{ $i }}" class="fa fa-star {{ $review->ratings >= $i ? 'checked' : '' }}"></label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">No reviews yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('review.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="addReviewModalLabel">Submit Review</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ratings">Rating</label>
                        <div class="star-rating" style="justify-content: center;">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="new-star-{{ $i }}" name="ratings" value="{{ $i }}">
                                <label for="new-star-{{ $i }}" class="fa fa-star"></label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comments">Comment</label>
                        <textarea name="comments" id="comments" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="uploads">Upload Images</label>
                        <input type="file" name="uploads[]" id="uploads" class="form-control-file" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.star-rating.display-only input:checked').siblings('label').addClass('checked');

    $('.star-rating input').on('change', function() {
        $(this).siblings('label').removeClass('checked');
        $(this).nextAll('label').addClass('checked');
        $(this).prevAll('label').addClass('checked');
    });
});
</script>
@endpush
