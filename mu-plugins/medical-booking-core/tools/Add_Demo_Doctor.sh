#!/bin/bash
# Add Demo Doctor CPT + Field
# Author: KhanhECB

# Số lượng bác sĩ demo
NUM_DOCTORS=20

for i in $(seq 1 $NUM_DOCTORS); do
(
    # Tạo post CPT doctor
    POST_ID=$(wp post create --post_type=doctor --post_title="Dr. Demo $i" --post_status=publish --porcelain)

    # Thêm meta ACF
    wp post meta add $POST_ID doctor_phone $((84000000000 + i))
    wp post meta add $POST_ID _doctor_phone "field_doctor_phone"

    wp post meta add $POST_ID doctor_email "demo$i@example.com"
    wp post meta add $POST_ID _doctor_email "field_doctor_email"

    QUALS=("thac_si" "tien_si" "ck1")
    QUAL=${QUALS[$((RANDOM % ${#QUALS[@]}))]}
    wp post meta add $POST_ID doctor_qualification $QUAL
    wp post meta add $POST_ID _doctor_qualification "field_doctor_qualification"

    EXP=$((RANDOM % 30 + 1))
    wp post meta add $POST_ID doctor_years_of_experience $EXP
    wp post meta add $POST_ID _doctor_years_of_experience "field_doctor_years_of_experience"

    POSITIONS=("truong_khoa" "bs_ck")
    POS=${POSITIONS[$((RANDOM % ${#POSITIONS[@]}))]}
    wp post meta add $POST_ID doctor_current_position $POS
    wp post meta add $POST_ID _doctor_current_position "field_doctor_current_position"

    DEPTS=("tim_mach" "mat" "rang_ham_mat")
    DEPT=${DEPTS[$((RANDOM % ${#DEPTS[@]}))]}
    wp post meta add $POST_ID doctor_department $DEPT
    wp post meta add $POST_ID _doctor_department "field_doctor_department"

    SCHEDULE=("Mon-Fri")
    SCHED=${SCHEDULE[$((RANDOM % ${#SCHEDULE[@]}))]}
    wp post meta add $POST_ID doctor_schedule $SCHED
    wp post meta add $POST_ID _doctor_schedule "field_doctor_schedule"

    BIO="Dr. Demo $i là một bác sĩ giàu kinh nghiệm."
    wp post meta add $POST_ID doctor_bio "$BIO"
    wp post meta add $POST_ID _doctor_bio "field_doctor_bio"

    echo "Đã tạo Dr. Demo $i (ID: $POST_ID)"
) &
done

# Chờ tất cả tiến trình hoàn tất
wait
echo "Hoàn tất tạo $NUM_DOCTORS bác sĩ demo!"
