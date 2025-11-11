<?php
/**
 * Partial: Advanced Search Form
 *
 * @var array<int, string> $tour_types
 * @var array<int, string> $tour_locations
 * @var array<int, string> $tour_persons
 * @var array<int, string> $tour_linked
 * @var array<string, string> $selected
 */
?>

<style>
    .filter {
        color: red;
        max-width: 250px;
    }

    #travel-booking-advanced-search-container {
        display: grid;
        gap: 24px;
        width: 100%;
        max-width: 100%;
        padding: 36px;
        border-radius: 4px;
        background: green;
    }

    #travel-booking-advanced-search-container label {
        display: inline-flex;
    }

    #travel-booking-advanced-search-container .form-row {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 300px !important;
        padding: 12px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.5);
    }
</style>

<div class="travel-booking-advanced-search-container">
    <form id="travel-booking-advanced-search-container">
        <div class="form-row">
            <label for="select-tour-type" class="">Loại hình tour</label>
            <select id="select-tour-type" name="tour_type" class="filter">
                <?php foreach ($tour_types as $type_id => $type_name): ?>
                    <option value="<?php echo esc_attr($type_id) ?>">
                        <?php echo esc_html($type_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label for="select-tour-location">Chọn địa điểm</label>
            <select id="select-tour-location" class="filter">
                <?php foreach ($tour_locations as $location_id => $location_name): ?>
                    <option value="<?php echo esc_attr($location_id) ?>">
                        <?php echo esc_html($location_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="form-row">
            <label for="select-tour-persons">Số người tối đa</label>
            <select id="select-tour-persons" class="filter">
                <?php foreach ($tour_persons as $id => $name): ?>
                    <option value="<?php echo esc_attr($id) ?>">
                        <?php echo esc_html($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label for="select-tour-linked">Tour ghép</label>
            <select id="select-tour-linked" class="filter">
                <?php foreach ($tour_linked as $id => $name): ?>
                    <option value="<?php echo esc_attr($id) ?>">
                        <?php echo esc_html($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button style="width: 100%" type="submit"> Tìm kiếm</button>
    </form>
</div>