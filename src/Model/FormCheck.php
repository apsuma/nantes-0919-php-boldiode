<?php


namespace App\Model;

/**
 * Use this class to check the various form of the website
 * Class FormCheck
 * @package App\Model
 */
class FormCheck
{
    /**
     * used to stock the POST of the form that needs to be checked
     * @var array
     */
    private $post;
    /**
     * used to validate if no error is present in the form
     * if an error is returned the variable will be changed to false
     * @var bool
     */
    private $valid = true;

    /**
     * FormCheck constructor.
     * @param array $post
     */
    public function __construct(array $post)
    {
        $this->post = $post;
    }

    public function shortText(string $postField): ?string
    {
        if (!isset($this->post[$postField]) || empty($this->post[$postField])) {
            $error = "Please enter a $postField";
            $this->valid = false;
        } elseif (!preg_match("/^[a-zA-Zéèùôûêîâç' -]*$/", $this->post[$postField])
            || strlen($this->post[$postField]) >= 255) {
            $error = "Please use a valid $postField and max 255 characters";
            $this->valid = false;
        }
        return isset($error)? $error: null;
    }

    public function text(string $postField): ?string
    {
        if (!isset($this->post[$postField]) || empty($this->post[$postField])) {
            $error = "Please enter a $postField";
            $this->valid = false;
        } elseif (!preg_match("/^[a-zA-Zéèùôûêîâç' -]*$/", $this->post[$postField])) {
            $error = "Please use only letters for the $postField";
            $this->valid = false;
        }
        return isset($error)? $error: null;
    }

    public function number(string $postField): ?string
    {
        if (!isset($this->post[$postField]) || empty($this->post[$postField])) {
            $error = "Please enter a number";
            $this->valid = false;
        } elseif (!is_numeric($this->post[$postField])) {
            $error = "Please use only numbers";
            $this->valid = false;
        }
        return isset($error)? $error: null;
    }

    public function email(string $postField): ?string
    {
        if (!isset($_POST[$postField]) || (empty($_POST[$postField]
                || (!filter_var($_POST[$postField], FILTER_VALIDATE_EMAIL))))) {
            $error = "Compléter votre email";
            $this->valid = false;
        }
        return isset($error)? $error: null;
    }

    public function getValid(): bool
    {
        return $this->valid;
    }
}
