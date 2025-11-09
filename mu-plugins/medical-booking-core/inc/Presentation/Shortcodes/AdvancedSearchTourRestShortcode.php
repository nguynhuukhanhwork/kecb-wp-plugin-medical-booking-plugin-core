<?php

namespace TravelBooking\Presentation\Shortcodes;

use TravelBooking\Repository\TourRepository;

class AdvancedSearchTourRestShortcode
{
    private static ?self $instance = null;

    private function __construct()
    {
        add_shortcode('advanced_search_tour', [$this, 'formSearch']);
        // add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton class " . __CLASS__);
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    public function formSearch(): string
    {

        // Get data
        $tour_repository = TourRepository::getInstance();

        $tour_locations = $tour_repository->geTourLocationTermNames();
        $tour_types = $tour_repository->getTourTypeTermNames();
        $tour_persons = $tour_repository->getTourPersonTermNames();
        $tour_linked = $tour_repository->getTourLinkedTermNames();


        ob_start();
        ?>
        <style>
            .filter {
                color: red;
                max-width: 250px;
            }
        </style>
        <div class="travel-booking-advanced-search-container">
            <form id="travel-booking-advanced-search-container">

                <select class="filter">
                    <option value="" disabled selected >Loại hình tour</option>
                    <?php foreach ($tour_types as $type_id => $type_name): ?>
                        <option value="<?php echo esc_attr($type_id)?>">
                            <?php echo esc_html($type_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="filter">
                    <option value="" disabled selected >Địa điểm đến</option>
                    <?php foreach ($tour_locations as $location_id => $location_name): ?>
                        <option value="<?php echo esc_attr($location_id)?>">
                            <?php echo esc_html($location_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="filter">
                    <option value="" disabled selected >Số người tối đa</option>
                    <?php foreach ($tour_persons as $id => $name): ?>
                        <option value="<?php echo esc_attr($id)?>">
                            <?php echo esc_html($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select class="filter">
                    <option value="" disabled selected >Tour ghép</option>
                    <?php foreach ($tour_linked as $id => $name): ?>
                        <option value="<?php echo esc_attr($id)?>">
                            <?php echo esc_html($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit"> Tìm kiếm </button>
            </form>
        </div>

        <?php return ob_get_clean();
    }
}