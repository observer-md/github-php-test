/**
 * Main JS
 */

var colorVotestData = [];

/**
 * Load votes by colorId
 * @param {Int} colorId 
 */
function loadVotes(colorId)
{
    $.getJSON("/?c=api&a=get-votes&id=" + colorId)
        .done(function (res) {
            
            let votesSum = 0;
            if (res.success && res.data && res.data.length) {
                $.each(res.data, (i, row) => {
                    votesSum += parseInt(row.votes);
                });
            }
            colorVotestData[colorId] = votesSum;

            $("#votes-" + colorId).html(votesSum);
        });
}

/**
 * Calculate total votes
 */
function calcTotal()
{
    let votesSum = 0;
    $.each(colorVotestData, (i, val) => {
        if (val) {
            votesSum += parseInt(val);
        }
    });

    $("#votes-total").html(votesSum);
}