<ul class="list-group list-group-flush admin-list">
    <a href="<?=base_url("index.php/admin/clients/edit/".$client->id)?>"
        <?php if($this->uri->segment(3) == 'edit') echo 'class="active"' ?>>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-top">
            Profile
        </li>
    </a>
    <a href="<?=base_url("index.php/admin/clients/pets/".$client->id)?>"
        <?php if($this->uri->segment(3) == 'pets') echo 'class="active"' ?>>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-top">
            Pets
            <span class="badge bg-primary rounded-pill">2</span>
        </li>
    </a>
    <a href="<?=base_url("index.php/admin/clients/bookings/".$client->id)?>"
        <?php if($this->uri->segment(3) == 'bookings') echo 'class="active"' ?>>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-top">
            Bookings
            <span class="badge bg-primary rounded-pill">95</span>
        </li>
    </a>
    <a href="#">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
            Quotations
            <span class="badge bg-primary rounded-pill">75</span>
        </li>
    </a>
    <a href="#">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
            Taken Services
            <span class="badge bg-primary rounded-pill">14</span>
        </li>
    </a>
    <a href="#">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
            Payments
            <span class="badge bg-primary rounded-pill">14</span>
        </li>
    </a>
    <a href="#">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
            Reviews
            <span class="badge bg-primary rounded-pill">14</span>
        </li>
    </a>
</ul>

<style type="text/css">
.admin-list a.active {
    background-color: rgb(52 97 255 / 10%);
    text-align: left !important;
}

.admin-list a.active li {
    color: #3461ff !important;
    text-align: left !important;
}

.admin-list a:hover {
    background-color: rgb(52 97 255 / 10%);
}

.admin-list a:hover li {
    color: #3461ff !important;
}
</style>