<?php
    class Playlist{
        private int $playlistId;
        private int $userId;
        private string $playlistName;
        private string $playlistDesc;
        private string $playlistPrivacy;

        function Playlist($id, $userId, $name, $description, $privacy){
            $this->playlistId = $id;
            $this->userId = $userId;
            $this->playlistName = $name;
            $this->playlistDesc = $description;
            $this->playlistPrivacy = $privacy;
        }

        function setPlaylistId($id){
            $this->playlistId = $id;
        }

        function getPlaylistId(){
            return $this->playlistId;
        }

        function setUserId($id){
            $this->userId = $id;
        }

        function getUserId(){
            return $this->userId;
        }

        function setPlaylistName($name){
            $this->playlistName = $name;
        }

        function getPlaylistName(){
            return $this->playlistName;
        }

        function setPlaylistDesc($description){
            $this->playlistDesc = $description;
        }

        function getPlaylistDesc(){
            return $this->playlistDesc;
        }

        function setPlaylistPrivacy($privacy){
            $this->playlistPrivacy = $privacy;
        }

        function getPlaylistPrivacy(){
            return $this->playlistPrivacy;
        }
    }
?>