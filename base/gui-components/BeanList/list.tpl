{if isset($items)}
    <table>
        <thead>
            <tr>
                {foreach $head val}

                    <th>{$val}</th>

                {/foreach}
            </tr>
        </thead>

        <tbody>

           {foreach $items val}
            <tr>

                {foreach $val val2}

                    <td>{$val2}</td>


                {/foreach}

            </tr>
           {/foreach}

        </tbody>



    </table>
{else}
    <div class="no-data">
        <p></p>
    </div>
{/if}