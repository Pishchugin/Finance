<?php

/**
 * Description of Pivot
 * @author Alexander Pishchugin
 */
class Soil {

    // uploads user's soil variance map picture to the server
    public function uploadUserSoilMap($pivotID, $user) {
        $mysqli = DBconnection::Connect();

        $query = "DELETE FROM `soil_links` WHERE `Pivot_location_pivot_ID`='$pivotID' AND `Link_Type`='1'";
        $result = $mysqli->query($query);

        mkdir("./uploads/soil_map", 0775, true);
        chmod("./uploads/soil_map", 0775);

        $ext = pathinfo(basename($_FILES['file']['name']), PATHINFO_EXTENSION);
        $target_path = "./uploads/soil_map/u" . $user . "-p" . $pivotID . "." . $ext;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            $query = "INSERT INTO `soil_links`(`Link_type`, `Link_path`, `Pivot_location_pivot_ID`) VALUES ('1', '$target_path', '$pivotID')";
            $result = $mysqli->query($query);
            if (!$result)
                return false;
            else
                return true;
        } else {
            return $target_path;
        }
    }

    // gets the list of soil layers if Multiple soil layer option is selected
    public function getSoilLayerType() {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `soil_layer_type`";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $layers = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $layers[] = $row;
            }
            return $layers;
        } else {
            return false;
        }
    }

    // retrieves soil variance map or cluster outcome picture for the pivot selected
    public function getSoilMapPicture($pivotID, $type) {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `soil_links` WHERE `Link_type`='$type' AND `Pivot_location_pivot_ID`='$pivotID'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row['Link_path'];
        } else {
            return false;
        }
    }

    // runs a third party app to cluster soil

    public function clusterSoil($pivotID, $unitsNumber) {
        $mysqli = DBconnection::Connect();

        /*  NEED TO BE FINISHED WHEN THE API IS GIVEN */

        $query = "DELETE FROM `soil_links` WHERE `Pivot_location_pivot_ID`='$pivotID' AND `Link_Type`='2'";
        $result = $mysqli->query($query);

        $clusterOutcome = "./uploads/cluster_outcome/cluster_outcome.png";
        $query = "INSERT INTO `soil_links`(`Link_type`, `Link_path`, `Pivot_location_pivot_ID`) VALUES ('2', '$clusterOutcome', '$pivotID')";
        if ($result != false)
            $result = $mysqli->query($query);


        $query = "DELETE FROM `soil_units` WHERE `Pivot_location_pivot_ID`='$pivotID'";
        if ($result != false)
            $result = $mysqli->query($query);

        $letters = "ABCDEFGHIJKLMNOPRST";
        for ($a = 0; $a < $unitsNumber; $a++) {
            $link = "./uploads/soil_units/soil_unit" . ($a + 1) . ".png";
            $name = "Soil " . substr($letters, $a, 1);
            $query = "INSERT INTO `soil_units`(`Unit_name`, `Link_path`, `Pivot_location_pivot_ID`) VALUES ('$name', '$link', '$pivotID')";
            if ($result != false)
                $result = $mysqli->query($query);
        }
        if (!$result)
            return false;
        else
            return $clusterOutcome;
    }

    // retrieves soil variance map for the pivot selected
    public function getSoilUnits($pivotID) {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `soil_units` WHERE `Pivot_location_pivot_ID`='$pivotID'";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $units = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $units[] = $row;
            }
            return $units;
        } else {
            return false;
        }
    }

    // finishes the pivot creation and saves the information about soil units in the DB
    public function finishPivot($pivotID, $units) {
        $mysqli = DBconnection::Connect();

        $result = false;
        for ($a = 0; $a < sizeof($units); $a++) {
            $id = $units[$a][0];
            $name = $units[$a][1];
            $lat = $units[$a][2];
            $long = $units[$a][3];

            $query = "UPDATE `soil_units` SET `Unit_name`='$name', `Latitude`='$lat', `Longitude`='$long' WHERE `Pivot_location_pivot_ID`='$pivotID' "
                    . "AND `ID`='$id'";
            $result = $mysqli->query($query);
        }
        if (!$result)
            return false;
        else
            return true;
    }

    /*  NEED TO BE FINISHED WHEN THE API IS GIVEN */

    // retrieves the list of soil layers for a soil unit (soil library)
    public function getSoilLibrary() {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `soil_library`";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $layers = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $layers[] = $row;
            }
            return $layers;
        } else {
            return false;
        }
    }

    // sets soil types for all units for the pivot selected
    public function setUnitsSoilType($pivotID, $soilTypes) {
        $mysqli = DBconnection::Connect();

        $result = false;
        for ($a = 0; $a < sizeof($soilTypes); $a++) {
            $id = $soilTypes[$a][0];
            $name = $soilTypes[$a][1];
            $type = $soilTypes[$a][2];

            $query = "UPDATE `soil_units` SET `Unit_name`='$name', `Soil_library_ID`='$type' WHERE `Pivot_location_pivot_ID`='$pivotID' "
                    . "AND `ID`='$id'";
            $result = $mysqli->query($query);
        }
        if (!$result)
            return false;
        else
            return true;
    }

    // retrieves the list of crops available
    public function getCropsList() {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `crop_type`";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            $crops = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $crops[] = $row;
            }
            return $crops;
        } else {
            return false;
        }
    }

    // returns DUMMY DATA, needed for testing only
    // returns Soil Moisture Stress Setting page as an array
    public function returnSoilMoistureStress($soilLibraryID) {
        if ($soilLibraryID == 1) {
            $moisture[] = array(1, 150, "0-150", "Sandy Loam", 0.1, 0.5, 0.53, 300);
            $moisture[] = array(2, 150, "150-300", "Sand", 0.05, 0.35, 0.42, 200);
            $moisture[] = array(3, 200, "300-500", "Clay", 0.25, 0.4, 0.42, 150);
            $moisture[] = array(4, 700, "500-1200", "Clay", 0.2, 0.35, 0.4, 50);
            $moisture[] = array(5, 200, "200-1400", "Clay", 0.22, 0.32, 0.45, 5);
        } else if ($soilLibraryID == 2) {
            $moisture[] = array(1, 100, "0-100", "Sandy Loam", 0.1, 0.6, 0.65, 300);
            $moisture[] = array(2, 60, "100-160", "Sand", 0.05, 0.4, 0.45, 200);
            $moisture[] = array(3, 200, "160-360", "Clay", 0.2, 0.35, 0.39, 150);
            $moisture[] = array(4, 200, "360-560", "Clay", 0.28, 0.2, 0.4, 50);
            $moisture[] = array(5, 100, "560-660", "Clay", 0.22, 0.3, 0.4, 5);
        } else if ($soilLibraryID == 3) {
            $moisture[] = array(1, 100, "0-100", "Sandy Loam", 0.15, 0.45, 0.6, 300);
            $moisture[] = array(2, 300, "100-400", "Sand", 0.15, 0.44, 0.52, 200);
            $moisture[] = array(3, 200, "400-600", "Clay", 0.2, 0.4, 0.42, 150);
            $moisture[] = array(4, 500, "600-1100", "Clay", 0.1, 0.23, 0.33, 50);
        } else if ($soilLibraryID == 4) {
            $moisture[] = array(1, 150, "0-150", "Sandy Loam", 0.1, 0.5, 0.53, 300);
            $moisture[] = array(2, 150, "150-300", "Sand", 0.05, 0.35, 0.42, 200);
            $moisture[] = array(3, 200, "300-500", "Clay", 0.25, 0.4, 0.42, 150);
            $moisture[] = array(4, 700, "500-1200", "Clay", 0.2, 0.35, 0.4, 50);
        } else if ($soilLibraryID == 5) {
            $moisture[] = array(1, 100, "0-100", "Sandy Loam", 0.1, 0.5, 0.53, 300);
            $moisture[] = array(2, 100, "100-200", "Sand", 0.05, 0.35, 0.42, 200);
            $moisture[] = array(3, 200, "200-400", "Clay", 0.25, 0.4, 0.42, 150);
            $moisture[] = array(4, 300, "400-700", "Clay", 0.2, 0.35, 0.4, 50);
            $moisture[] = array(5, 500, "700-1200", "Clay", 0.22, 0.32, 0.45, 5);
        }
        return $moisture;
    }

}
