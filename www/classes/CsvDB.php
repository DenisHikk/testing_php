<?php
namespace www\classes;

class CsvDB {
    private $conn;

    function __construct(ConnectToDB $conn) {
        $this->conn = $conn;
    }

    public function importFromCsvToDB($file, $userId) {        
        try{
            $handle = fopen($file, 'r');
            $updateCount = 0;
            $importCount = 0;
            if($handle === false) {
                throw new \Exception('Wrong open file');
            }
            $header = fgetcsv($handle, 0, ";", "\"", "\\");
            $placeholder = implode(",", $header);
            $sql = "insert into products(id, name, name_trans, price, small_text, big_text, user_id) 
            values(:id, :name, :name_trans, :price, :small_text, :big_text, :user_id)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                name_trans = VALUES(name_trans),
                price = VALUES(price),
                small_text = VALUES(small_text),
                big_text = VALUES(big_text),
                user_id = VALUES(user_id)";

            while($row = fgetcsv($handle, 0,";", "\"", "\\") ) {
                $data = [
                    ':id' => mb_convert_encoding($row[0], 'UTF-8', "windows-1251"),
                    ':name' => mb_convert_encoding( $row[1], 'UTF-8', "windows-1251"),
                    ':name_trans' => mb_convert_encoding($row[2], 'UTF-8', "windows-1251"),
                    ':price' => mb_convert_encoding($row[3], 'UTF-8', "windows-1251"),
                    ':small_text' => mb_convert_encoding($row[4], 'UTF-8', "windows-1251"),
                    ':big_text' => mb_convert_encoding($row[5], 'UTF-8', "windows-1251"),
                    ':user_id' => $userId
                ];
                if(strlen($data[':small_text']) > 30) {
                    $data[':small_text'] = mb_substr($data[':big_text'],0, 30);
                }
                $stmt = $this->conn->query($sql, $data);
                if ($stmt->rowCount() > 0) {
                    if ($stmt->rowCount() > 1) {
                        $updateCount++;
                    } else {
                        $importCount++;
                    }
                }
            }
            fclose($handle);
            echo json_encode([
                'update' => $updateCount,
                'import' => $importCount
            ]);;

        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function exportToCsvFromDB($userId) {
        $datas = $this->conn->fetchAll("select * from products where user_id = " . $userId);
        $fileName = strval($userId)."file.csv";
        $files = fopen($fileName, 'w');
        $header = ['id', 'name', 'name_trans', 'price', 'small_text', 'big_text'];
        fputcsv($files, $header, ";",  "\"", "\\");
        foreach($datas as $row) {
            foreach($row as $key => $value) {
                $row[$key] = mb_convert_encoding($value, "windows-1251", "UTF-8");
            }
            $row["user_id"] = '';
            fputcsv($files, $row, ";", "\"", "\\");
        }
        fclose($files);
        return $fileName;
    }

}