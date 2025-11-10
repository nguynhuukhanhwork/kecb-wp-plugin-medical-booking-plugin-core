<?php

namespace TravelBooking\Infrastructure\Integrations\CF7;

use TravelBooking\Repository\TourRepository;

final class RegistrarTagOptions
{
    private static ?self $instance = null;
    private TourRepository $tour_repo;

    private function __construct()
    {
        $this->tour_repo = TourRepository::getInstance();
        add_filter('wpcf7_form_tag_data_option', [$this, 'tagSelectTourType'], 10, 3);
        add_filter('wpcf7_form_tag_data_option', [$this, 'tagSelectTourCost'], 10, 3);
        add_filter('wpcf7_form_tag_data_option', [$this, 'tagSelectTourLinked'], 10, 3);
        add_filter('wpcf7_form_tag_data_option', [$this, 'tagSelectTourPerson'], 10, 3);
        // add_filter('wpcf7_form_tag_data_option', [$this, 'debugAllTagOptions'], 9, 3); // Test debug
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public function tagSelectTourType($data, $options, $args)
    {
        // Chỉ xử lý khi tên data là 'tour-type-terms'
        if (!in_array('tour-type-terms', $options)) {
            return $data;
        }

        // Lấy danh sách term names từ repository
        $term_names = $this->tour_repo->getTourTypeTermNames();

        // Check empty
        if (empty($term_names)) {
            return [];
        }

        // CF7 yêu cầu mảng giá trị đơn giản (không key => value nếu không cần)
        return $term_names;
    }

    public function tagSelectTourCost($data, $options, $args) {
        if (!in_array('tour-cost-terms', $options)) {
            return $data;
        }

        return $this->tour_repo->getTourCostTermNames();
    }

    public function tagSelectTourLinked($data, $options, $args)
    {
        if (!in_array('tour-link-terms', $options)) {
            return $data;
        }

        return $this->tour_repo->getTourLinkedTermNames();
    }

    public function tagSelectTourPerson($data, $options, $args)
    {
        if (!in_array('tour-person-terms', $options)) {
            return $data;
        }

        return $this->tour_repo->getTourPersonTermNames();
    }

    public function debugAllTagOptions($data, $options, $args)
    {
        error_log('CF7 options: ' . print_r($options, true));
        return $data;
    }
}
