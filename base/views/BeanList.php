<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 26/12/2017
 * Time: 11:18 AM
 */
class BeanList
{

    protected $dataToPrint;

    /**
     * BeanList constructor.
     * @param IPrintable objects array
     */
    public function __construct(array $printables)
    {

        $dataToPrint = [];

        foreach ($printables as $printable){

            if(is_a($printable,"IPrintable"))
            {
                $dataToPrint[]=$printable->printSerialize();
            }

        }
        $this->dataToPrint = $dataToPrint;
    }

    public function getHTML()
    {
        ob_start();
        ?>


        <?php
        if(count($this->dataToPrint) > 0)
        {

            $heads = array_keys(reset($this->dataToPrint));

                ?>
                <table>
                    <thead>
                    <tr>
                        <?php
                        foreach ($heads as $head)
                        {
                            ?>

                                <th>
                                    <?Php echo $head?>
                                </th>

                            <?php
                        }
                        ?>
                    </tr>
                    </thead>

                    <tbody>

                    <?Php
            foreach ($this->dataToPrint as $k=>$bean)
            {

                ?>
                        <tr>
                            <?php
                            foreach ($bean as $var){
                                ?>
                                <td><?php  echo $var;?></td>
                                <?Php
                            }
                            ?>
                        </tr>
                <?Php

            }
                ?>
                    </tbody>
                </table>
                <?php


        }
        else
        {
            ?>
            <div class="no-data">
                <p></p>
            </div>
            <?Php
        }
        ?>




        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;

    }

}