<?php

function fee_defaulter($admission_no)
{
    include($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');
    $date = date("Y-m-d");
    $fee_sql = "select users.disable_auto_block_report_card  as disable_auto_block_report_card,
       students.roll_number,
       students.id,
       students.admission_no,
       students.first_name,
       students.middle_name,
       students.last_name,
       students.batch_id,
       sum(balance)                            fee_due,
       students.immediate_contact_id,
       count(IF(balance > 0, balance, NULL))   fee_collections_count,
       students.sibling_id,
       students.phone2
from students
         inner join (
    select ff.student_id      student_id,
           CONCAT('f', fc.id) collection_id,
           fc.due_date        due_date,
           fc.name            collection_name,
           ff.balance         balance,
           ff.batch_id        batch_id
    FROM `finance_fees` ff
             LEFT OUTER JOIN students st on st.id = ff.student_id
             INNER JOIN `finance_fee_collections` fc ON `fc`.id = `ff`.fee_collection_id
             LEFT JOIN `fee_accounts` fa ON `fa`.id = fc.fee_account_id
             WHERE `fc`.`is_deleted` = 0 and fc.due_date < $date and ff.balance > 0
             and st.id IS NOT NULL and fa.id IS NULL OR fa.is_deleted = false)
    finance on finance.student_id=students.id INNER JOIN users on students.user_id = users.id
    where students.admission_no = $admission_no
group by students.id ;";
//echo $fee_sql;
    $fee_result = $conn->query($fee_sql);
    if ($fee_result->num_rows > 0) {
        $fee_row = $fee_result->fetch_assoc();
//        echo $fee_row['fee_due'] ;
        if ($fee_row['fee_due'] > 0)
            return true;
        else return false;
    } else return false;
}
