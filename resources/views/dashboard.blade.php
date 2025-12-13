@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
@keyframes slideInLeft {
  from {
    transform: translateX(-100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes scaleInCenter {
  from {
    transform: scale(0);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes slideInTop {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes swingInRightFwd {
  0% {
    transform: rotateY(100deg);
    transform-origin: 0% 50%;
    opacity: 0;
  }
  100% {
    transform: rotateY(0);
    transform-origin: 0% 50%;
    opacity: 1;
  }
}

.animate-slideInLeft {
  animation: slideInLeft 0.5s ease-out forwards;
}

.animate-slideInRight {
  animation: slideInRight 0.5s ease-out forwards;
}

.animate-scaleInCenter {
  animation: scaleInCenter 0.5s ease-out forwards;
}

.animate-slideInTop {
  animation: slideInTop 0.5s ease-out forwards;
}

.animate-swingInRightFwd {
  animation: swingInRightFwd 0.5s ease-out forwards;
}

.content-bg {
  background-color: #e5e7eb;
  border-radius: 6px;
}

.gray-bg {
  background-color: #9ca3af;
}

.gray-light-bg {
  background-color: #f3f4f6;
}
</style>

<div class="min-vh-100 overflow-hidden p-3">
  <div class="container mx-auto">
    <!-- Header Section -->
    <div class="d-flex flex-wrap gap-3 mb-4">
      <div class="w-100 col-md-2">
        <div class="animate-slideInLeft gray-bg rounded h-20 d-flex align-items-center justify-content-center">
          <span class="text-white fw-bold">Sidebar</span>
        </div>
      </div>
      <div class="w-100 col-md-10">
        <div class="animate-slideInRight content-bg h-20 d-flex align-items-center justify-content-center">
          <span class="fw-bold">Main Content</span>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="d-flex flex-wrap gap-5 w-100 mb-5">
      <div class="w-100 col-lg-8">
        <div class="animate-scaleInCenter content-bg h-96 d-flex align-items-center justify-content-center">
          <span class="fw-bold">Main Dashboard Content</span>
        </div>
      </div>
      <div class="w-100 col-lg-4">
        <div class="animate-swingInRightFwd">
          <div class="content-bg h-12 mb-3 d-flex align-items-center justify-content-center">
            <span class="fw-bold">Widget Title</span>
          </div>
          <div class="d-flex flex-wrap gap-3 w-100 mb-3">
            @php
              $widths = [72, 64, 96, 64, 72];
            @endphp
            @foreach($widths as $width)
            <div class="w-100">
              <div class="content-bg h-5 d-flex align-items-center justify-content-center"
                   style="max-width: 100%; width: {{ $width }}px;">
                <span class="small fw-bold">Item</span>
              </div>
            </div>
            @endforeach
          </div>
          <div class="w-32 h-12 content-bg rounded-pill d-flex align-items-center justify-content-center">
            <span class="fw-bold">Button</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer Section -->
    <div class="animate-slideInTop d-flex flex-column align-items-center mb-5">
      <div class="w-56 h-7 gray-light-bg rounded mb-3 d-flex align-items-center justify-content-center">
        <span class="fw-bold">Title</span>
      </div>
      <div class="w-96 h-12 content-bg rounded d-flex align-items-center justify-content-center">
        <span class="fw-bold">Subtitle</span>
      </div>
    </div>

    <!-- Cards Grid -->
    <div class="d-flex flex-wrap gap-5">
      @for($i = 0; $i < 4; $i++)
      <div class="w-100 col-md-6 col-lg-3">
        <div class="animate-slideInLeft content-bg h-56 d-flex align-items-center justify-content-center"
             style="animation-duration: {{ 2 - $i * 0.5 }}s; animation-delay: {{ $i * 1 }}s;">
          <span class="fw-bold">Card {{ $i + 1 }}</span>
        </div>
      </div>
      @endfor
    </div>
  </div>
</div>
@endsection