# railanalytics
Laravel analytics system for using tracking code in your application for google analytics/adwords, facebook pixel, etc.

TrackProvider
    GoogleAnalyticsTrackProvider
    GoogleAdwordsTrackProvider
    GoogleTagManagerTrackProvider
    FacebookPixelTrackProvider
    
functions:
    GetHeadTopTrackingCode
    GetHeadBottomTrackingCode
    GetBodyTopTrackingCode
    GetBodyBottomTrackingCode
    TrackBase
    TrackProductImpression
    TrackAddToCart
    TrackInitiateCheckout
    TrackAddPaymentInformation
    TrackTransaction
    TrackRegistration
    
Track
    GetHeadTopTrackingCode($trackGroupName)
    GetHeadBottomTrackingCode($trackGroupName)
    GetBodyTopTrackingCode($trackGroupName)
    GetBodyBottomTrackingCode($trackGroupName)
    TrackBase($trackGroupName)
    TrackProductImpression($trackGroupName, $id, $name, $category, $value, $currency = null)
    TrackProductDetailsImpression($trackGroupName, $id, $name, $category, $value, $currency = null)
    TrackAddToCart($trackGroupName, $id, $name, $category, $value, $quantity, $currency = null)
    TrackInitiateCheckout
    TrackAddPaymentInformation
    TrackTransaction
    TrackRegistration