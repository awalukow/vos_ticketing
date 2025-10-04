@extends('layouts.app')
@section('title', 'Pilih Event')

@section('styles')
  <style>
    body {
      background-color: #fafafa;
      font-family: 'Segoe UI', sans-serif;
    }

    .header-bg {
      background: linear-gradient(135deg, #9c27b0, #6a1b9a);
      padding: 2rem 1rem;
      text-align: center;
      color: white;
      border-bottom-left-radius: 40px;
      border-bottom-right-radius: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .header-bg h1 {
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: -0.5px;
      margin: 0;
    }

    .header-bg p {
      font-size: 1rem;
      opacity: 0.9;
      margin-top: 0.5rem;
    }

    .container {
      padding: 2rem 1rem;
    }

    .events-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      justify-content: center;
      max-width: 1000px;
      margin: 0 auto;
    }

    .event-card {
      width: 100%;
      max-width: 350px;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      position: relative;
      cursor: pointer;
      background: white;
      border: 2px solid transparent;
    }

    .event-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    }

    .event-card.selected {
      border-color: #a81d20;
    }

    .event-image {
      width: 100%;
      height: 160px;
      object-fit: cover;
      background: #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: #666;
      font-weight: 500;
    }

    .event-title {
      text-align: center;
      padding: 1rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: #2d3748;
      margin: 0;
    }

    /* Event Date Style */
    .event-date {
      text-align: center;
      font-size: 0.9rem;
      color: #999;
    }

    /* Hidden radio */
    .event-radio {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
      z-index: 2;
    }

    /* Checkmark indicator */
    .checkmark {
      position: absolute;
      bottom: 12px;
      right: 12px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 3;
      transition: all 0.2s ease;
    }

    .event-radio:checked + .checkmark {
      background: #a81d20;
      border-color: #a81d20;
    }

    .event-radio:checked + .checkmark::after {
      content: '✓';
      color: white;
      font-size: 14px;
      font-weight: bold;
    }

    /* Spinner */
    .spinner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      display: none;
      border: 4px solid #f3f3f3; /* Light grey */
      border-top: 4px solid #a81d20; /* Red */
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Button Styles */
    .btn-primary {
      background: #a81d20;
      color: white;
      border: none;
      padding: 14px 36px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 24px;
      box-shadow: 0 4px 12px rgba(168, 29, 32, 0.3);
      transition: all 0.2s ease;
      margin-top: 2rem;
      display: inline-block;
    }

    .btn-primary:hover {
      background: #6c1111;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(168, 29, 32, 0.4);
    }

    @media (max-width: 768px) {
      .events-grid {
        flex-direction: column;
        align-items: stretch;
      }
      .event-card {
        max-width: 100%;
      }
    }
  </style>
@endsection

@section('content')
  <div class="container">
    <form method="POST" action="{{ route('store') }}" id="eventForm">
      @csrf

      <div class="events-grid">
        @foreach ($category as $val)
          <label class="event-card" data-id="{{ $val->id }}">
            <!-- Hidden Radio -->
            <input type="radio" name="category" value="{{ $val->id }}" class="event-radio" required />

            <!-- Placeholder Image -->
            <div class="event-image">
              {{ $val->name }}
            </div>

            <!-- Title -->
            <h3 class="event-title">{{ $val->name }}</h3>

            <!-- Event Date -->
            <div class="event-date">
              {{ \Carbon\Carbon::parse($val->EventDate)->format('d M Y') }}
            </div>

            <!-- Checkmark Indicator -->
            <span class="checkmark"></span>
          </label>
        @endforeach
      </div>

      <!-- Spinner -->
      <div id="spinner" class="spinner"></div>
      
    </form>
  </div>
@endsection

@section('script')
  <script>
    document.querySelectorAll('.event-card').forEach(card => {
      card.addEventListener('click', function () {
        // Remove previous selection
        document.querySelectorAll('.event-card').forEach(c => c.classList.remove('selected'));

        // Highlight the selected card
        this.classList.add('selected');

        // Check the corresponding radio button
        const radio = this.querySelector('.event-radio');
        if (radio) {
          radio.checked = true;

          // Show spinner and add slight delay before submitting
          const spinner = document.getElementById('spinner');
          spinner.style.display = 'block';  // Show the spinner

          // Delay form submission by 1 second
          setTimeout(function() {
            // Submit the form after delay
            document.getElementById('eventForm').submit();
          }, 1000);  // 1000 ms = 1 second
        }
      });
    });
  </script>
@endsection
