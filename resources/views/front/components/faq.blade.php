<div class="forChart">
    <h5 class="ql-align-center"><strong>Fast-Satta-Result - The World's Best Satta Bazar Website</strong></h5>
    @foreach ( $qa as $quews )
        
    
    <h5 class="ql-align-center"><strong>{{ $quews->question }}</strong></h5>
    <p>{{ $quews->answer }}</p>
        @endforeach
    
    
    
        <h5 class="ql-align-center"><strong>Frequently Asked Question About Fast-Satta-Result</strong></h5>
        @foreach ( $faq as $quews )
            
        
    <h5 class="ql-align-center"><strong>{{ $quews->question }}</strong></h5>
    <p>{{ $quews->answer }}</p>
      @endforeach
    </div>