<?php
class Model_Registration extends Model {

    public function store_client($data)
    {
        $con = $this->db();

        if ($con->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        $query = "INSERT INTO `clients` (
                    `campaign_name`, 
                    `email`, 
                    `full_name`, 
                    `lead_cost`, 
                    `phone`, 
                    `address`, 
                    `city`, 
                    `state`, 
                    `country`, 
                    `status`, 
                    `abn`, 
                    `authorised_person`, 
                    `position`, 
                    `name_on_card`, 
                    `credit_card_number`, 
                    `expires_mm`, 
                    `expires_yy`, 
                    `cvc`) 
                    VALUES (
                        '".$data['campaign_name']."',
                        '".$data['email']."',
                        '".$data['full_name']."',
                        '0',
                        '".$data['phone']."',
                        '".$data['address']."',
                        '".$data['city']."',
                        '".$data['state']."',
                        '".$data['country']."',
                        '1',
                        '".$data['abn']."',
                        '".$data['authorised_person']."',
                        '".$data['position']."',
                        '".$data['name_on_card']."',
                        '".$data['credit_card_number']."',
                        '".$data['expires_mm']."',
                        '".$data['expires_yy']."',
                        '".$data['cvc']."'
                    );";
        $result = $con->query($query);
        if( $result ) {
            echo('True!');
        } else {
            $con->close();
            echo('Error');
            return FALSE;
        }

    }
}
