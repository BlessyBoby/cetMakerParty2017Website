<?php

header("Content-type: text/css; charset: UTF-8");
require("../../../CONSTANTS.php");

?>

#applySection
{
  color: rgba(0, 0, 0, 0.6);
  /*color: #333333;*/
}

#applyButtonsRow
{
  /*position: absolute;*/
  width: 100%;
  display: flex;
  margin: 0;
  justify-content: center;
  align-items: center;
}

#workApplyButton, #experienceApplyButton
{
  text-align: center;
}

#applyLine
{
  padding-top: 10px;
}

#inputWorkName
{
  padding: 5px;
  border-radius: 0px;
  border: 1px grey;
  margin-top: 10px;
}

#textareaWorkDescription
{
  padding: 5px;
  border-radius: 0px;
  border: 1px grey;
  margin-top: 10px;
}
