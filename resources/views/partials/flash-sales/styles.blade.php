@pushOnce('styles')
<style>
    .flash-sale-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.75rem;
        border-left: 3px solid #dc3545 !important;
    }
    
    .flash-sale-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        height: 200px;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-image-container:hover .overlay {
        opacity: 1;
    }
    
    .product-image-container:hover .product-image {
        transform: scale(1.1);
    }
    
    /* For smaller card layouts (like in index page) */
    .flash-sale-small .product-image-container {
        height: 150px;
    }
</style>
@endPushOnce 