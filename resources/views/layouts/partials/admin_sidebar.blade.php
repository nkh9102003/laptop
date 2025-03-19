<div id="admin-sidebar" class="sidebar">
    <div class="sidebar-header">
        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Management</h5>
        <button id="close-sidebar" class="btn btn-link p-0 d-xl-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-laptop me-2"></i> {{ __('messages.products') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.brands.index') }}">
                    <i class="fas fa-tag me-2"></i> {{ __('messages.brand') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag me-2"></i> {{ __('messages.orders') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.specifications.types') }}">
                    <i class="fas fa-cogs me-2"></i> Specifications
                </a>
            </li>
        </ul>
    </div>
</div>

<div id="sidebar-overlay"></div> 