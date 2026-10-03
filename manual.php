<?php
require_once './incld/header.php';

?>

<!-- Header -->
<header class="w3-container w3-padding">
    <h2><i class="fa fa-circle-info w3-xlarge"></i> Manual</h2>
</header>
<div class="w3-container w3-large">
    <div class="w3-col l3 m3 s3 side-menu">
        <div class="sticky w3-margin-top">
            <ul><a href="#Top">FINSIMP</a></ul>
            <ul><a href="#Overview">Overview</a></ul>
            <ul><a href="#Accounts">Step 1: Create Accounts</a></ul>
            <ul><a href="#Assets">Step 2: Assign Assets</a></ul>
            <ul><a href="#Liabilities">Step 3: Assign Liabilities</a></ul>
            <ul><a href="#Budgets">Step 4: Create Budgets</a></ul>
            <ul>Recording Transactions
                <ul>
                    <li><a href="#Income">Income</a></li>
                    <li><a href="#Inc-Exmpls">Income Examples</a></li>
                    <li><a href="#Expense">Expense</a></li>
                    <li><a href="#Exp-Exmpls">Expense Examples</a></li>
                    <li><a href="#Transfers">Transfers</a></li>
                </ul>
            </ul>
            <ul><a href="#User">User</a></ul>
            <ul><a href="#Summary">Summary</a></ul>        
        </div>
    </div>
    <div class="w3-col l9 m9 s10 manual">
        <div class="w3-margin w3-padding" id="FINSIMP">
            <h2>FinSimp | User Manual</h2>
            <hr></hr>
            <img class="imgs" src="./images/front_page.jpg" />
            <hr></hr>
            <div class="w3-center">
                <h4>Take control of your finances and stop being controlled by your finances.</h4>
                <ul class="centered">
                    <li>Inherently Secure - Personally identifiable information is not needed</li>
                    <li>Simple in Design - Accounting degree is not required</li>
                    <li>Accurate - Charts, graphs, and info cards for clarity</li>
                </ul>
            </div>
        </div>
        <br />
        <div class="w3-margin w3-padding dynamic-img" id="letter">
            <p class="w3-margin">Dear User,</p>
            <p class="w3-margin">FinSimp is the result of a collaboration between my wife and me. First, let me briefly
            explain some background. My wife, Kandi, has no formal financial education but was
            formally educated and trained as a nurse and midwife. She opened up her own local
            practice in Houston, TX. Kandi genuinely loved her work but absolutely abhorred the
            required paperwork, and believes that computers were purposely designed to make
            our lives unduly complicated. I suppose my wife is not alone in feeling this way. My
            formal education is a combination of finance and information technology, primarily
            from the University of Houston but also from online programmers and software
            engineers more skilled than myself. My wife will often complain that I spend too much
            time with what she calls my girlfriend, my computer. However, I consider it an
            extension of my imagination and a canvas for creating useful and meaningful content.
            She helps to keep me grounded.</p>
            <p class="w3-margin">Open-source or "free" software is available, and some are great expressions of a
            complete accounting program such as HomeBank and GnuCash, among many others.
            These programs are feature-rich and can be used as an accurate accounting platform
            for the home and professional user. However, the average home user or those without
            an accounting background will most likely find these programs difficult and confusing.
            After spending several years using and testing many options and with the insistence of
            my wife and her desire to easily manage her finances, we decided to attempt to create
            our own.</p>
            <p class="w3-margin">FinSimp incorporates only the most important aspects of personal accounting and
            financial analysis. Charts, graphs, budgets, card displays, reports, and financial ratios
            are utilized to produce a visual manifestation of spending habits, financial health,
            account balances, and investment activities. A primary goal of FinSimp was to
            maintain the double entry accuracy without needing to learn the method and
            vernacular. This is accomplished through automation and simplified transactions.
            Another important goal was to provide a secure platform. FinSimp is inherently secure
            as no financial or personal information (other than an email) is asked or required. In
            the unlikely event of a data breach, the only personally identifiable information that
            could be accessed by the perpetrator is an email. We require an email so that we have
            a means to contact you regarding your account or to provide you with timely updates
            and announcements.</p>
            <p class="w3-margin">Ultimately, FinSimp is designed for the average home user to be secure, mobile-
            friendly, accurate, and meaningful. We hope that this program will help you achieve
            your financial goals or at the very least, identify the reason for the flight of your
            money.</p>
            <p class="w3-margin">Best Regards,</p>
            <br />
            <p class="w3-margin">Zach & Kandi</p>
            <p class="w3-margin">PS: My wife now exhibits less hostility toward her computer.</p>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Overview">
            <h3><u>Overview</u></h3>
            <p class="w3-margin">The Overview page provides a summary view of financial health. This page includes a
                side navigation panel, balance card displays across the top, a doughnut chart and
                column chart, and Financial Ratio card displays across the bottom.
            <img class="zoom" src="./images/overview.png" />
            <p class="w3-margin">The balance cards display amounts for Assets, Income, Budget, Expense, and Liabilities.</p>
            <ul class="w3-margin-left">
                <li>Assets - total liquid assets (Cash Equivalents) and other assets (Real & Personal Properties, Stocks, and Bonds)</li>
                <li>Income - total current month and annual income</li>
                <li>Budget - total current month and annual budgets</li>
                <li>Expense - total current month and annual expense</li>
                    * Does not account for payments on Liabilities (i.e. repayments)
                <li>Liabilities - total current and long term liabilities</li>
            </ul>
            <img class="zoom" src="./images/index_charts.png" />
            <p class="w3-margin">The Cash Accounts doughnut chart displays the cash equivalent asset account balances</p>
            <p class="w3-margin">The Cash Flow & Credit column chart displays the income, and cash / credit expenses.</p>
            <img class="zoom" src="./images/index_ratios.png" />
            <p class="w3-margin">The Financial Health ratio cards include Cash, Wealth, Debt, and Expense.</p>
            <ul class="w3-margin-left">
                <li>Cash -  current trend and next 8 months of expenses - Cash Ratio (minimum of 75%)</li>
                <li>Wealth - current trend and last 12 months of wealth accumulation - computed for lifetime (minimum of 15%)</li>
                <li>Debt - current and long term liabilities to assets - Debt ratio (maximum of 35%)</li>
                <li>Expense - current trend and last 12 months expense to income - Cost Income ratio (maximum of 75%)</li>
                    * Does not account for payments on Liabilities (i.e. repayments)
            </ul>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Accounts">
            <h4><u>STEP 1: Create Accounts</u></h4>
            <p class="w3-margin">To begin, select the Accounts link from the side menu. On the Accounts page you will be greeted
                with an empty Chart of Accounts. Here you can choose to select a predefined set of accounts
                or add/edit accounts.</p>
            <p class="w3-margin">First, select the Personal Accts button to populate your Chart of Accounts with a
                standard list of frequently used accounts and those accounts necessary for the correct
                operation of the program. You can add, edit, and delete any account now or later. The
                changes to the account take affect site wide.</p>
            <img class="zoom" src="./images/accts.png" />
            <p class="w3-margin"><b class="w3-green">TIP:</b> Refresh the page after adding or editing entries to update the charts and display cards.</p>
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> Both “Loans” and “Interest” Income accounts are required and cannot be altered.</p>
            <p class="w3-margin"><b class="w3-red">WARNING:</b> If you delete an account here every recorded transaction that is
                associated with that account will also be deleted. Try Hidding the account instead if you no longer want to see it but would like to use it again later.</p>
            <p class="w3-margin">The Chart of Accounts consists of 5 categories:</p>
            <p class="w3-margin">Assets - Accounts used for holding and accumulating property and value</p>
            <ul class="w3-margin-left">
                <li>Cash Equivalents (e.g. checking, savings, money market, cash)</li>
                <li>Stocks (e.g. Preferred or Common)</li>
                <li>Bonds (I-bonds, EE bonds, etc.)</li>
                <li>Real Property (e.g. house, land, equipment, and all other affixed items)</li>
                <li>Personal Property (e.g. Jewelry, paintings, car, or any movable property)</li>
            </ul>
            <p class="w3-margin">Liabilities - Accounts used for holding or accumulating borrowed money</p>
            <ul class="w3-margin-left">
                <li>Credit cards and loans</li>
            </ul>
            <p class="w3-margin">Income - Accounts used for recording the reciept of money</p>
            <ul class="w3-margin-left">
                <li>Include any accounts used to record income from working (operating) or investing (financing)
                    activities (e.g. Wages, Salary, SSI, Pension, Dividend & Interest, Capital Gains, Loans, etc.).</li>
                <li>Loans are recorded here but they do not count as earned income unless they are
                    forgiven or the portion that is forgiven.</li>
            </ul>
            <p class="w3-margin">Expense - Accounts used for recording the expenditure of money</p>
            <ul class="w3-margin-left">
                <li>Include all spending accounts that detail your expenditures (e.g. Household, Dining
                    Out, Automobile, Office Supplies, etc).</li>
            </ul>
            <p class="w3-margin">Equity</p>
            <ul class="w3-margin-left">
                <li>Not included in a personal account. Generally, it is the amount of money funded by
                    owners and shareholders to start a business and keep it operating, and it also
                    represents the value of a company or organization minus its debts.</li>
            </ul>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Assets">
            <h4><u>STEP 2: Assign Assets</u></h4>
            <p class="w3-margin">Select the Assets link from the side menu.</p>
            <p class="w3-margin">The Assets page displays a doughnut chart (Asset Portfolio) of all assets and a pie
                chart (Risk Assessment) of total risks (low, moderate, high) associated with each asset
                account.</p>
            <img class="zoom" src="./images/asset-port.png" />
            <p class="w3-margin">Below the charts, each asset account is represented as a display card of running
                balances.</p>
            <img class="zoom" src="./images/asset-dispcards.png" />
            <p class="w3-margin">Select the oversize Asset panel at the bottom of the page to display the asset table.
                Select the +Asset button and assign a beginning balance, APY(Annual Percentage Yield), risk level, and asset type to
                each asset account you created in the previous step. ONLY assign assets on this page
                and DO NOT record transactions.</p>
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> Only enter numbers for Amounts (e.g. 1500 or 1500.00 but NOT 1,500.00 or $1500) and only numbers for 
                APY (e.g. 2.30 or 2 but NOT 2.3% or 2%)</p>
            <img class="zoom" src="./images/asset-table.png" />
            <p class="w3-margin">Depending on your level of risk appetite and risk tolerance, the Risk Assessment pie
                chart (pictured at the top of the page) will display the risk profile based on your
                financing activity.</p>
            <img class="zoom" src="./images/transfers.png" />
            <p class="w3-margin">To transfer money from one Asset account to another select the Transfers link from the
                side menu. DO NOT record expenses or income transactions here.</p>
            <p class="w3-margin">For example: You could shift money from a Checking account to a Savings account.

        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Liabilities">
            <h4><u>STEP 3: Assign Liabilities</u></h4>
            <p class="w3-margin">Select the Liabilities link from the side menu.</p>
            <img class="zoom" src="./images/liab-charts.png" />
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> ONLY assign liabilities on this page and DO NOT record transactions.</p>
            <p class="w3-margin"><b class="w3-green">TIP:</b> When assigning balances to credit cards, assign a 0.00 balance to newly
            opened credit cards. Then record the new purchase using that account in the
            Expense table and select “Credit” as the “Type”. The credit account will
            increase by that amount automatically and the corresponding expense account
            will also increase by the same amount.</p>
            <p class="w3-margin">To assist with tracking payments, the Liability Repayments section displays the most
            recent liability payment and date of payment for each liability.</p>
            <p class="w3-margin">Select the oversize Liability panel to display the liability table. Just like in step 2, add
            liabilities by assigning a beginning balance and type (Long Term or Current) to each
            liability account you created in step 1. A liability is considered Long Term if payments
            will continue past 1 year and Current if payments will cease in less than 1 year.</p>
            <img class="zoom" src="./images/liab-table.png" />
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Budgets">
            <h4><u>STEP 4: Create Budgets</u></h4>
            <p class="w3-margin">Select the Budgets link from the side menu.</p>
            <img class="zoom" src="./images/budget-charts.png" />
            <p class="w3-margin">Each budget can be considered a micro goal. Making small corrections to your
            financial habits will bring you closer to the total expense savings goal and
            financial stability.</p>
            <p class="w3-margin">Each monthly budget balance is automatically adjusted to reflect the
            remaining total budget for the remaining months of the year</p>
            <p class="w3-margin">On the Budgets page, select the Budgets panel located at the bottom.</p>
            <p class="w3-margin">The Budget List presents each budget as entered into the system. The Spending
            column and Balances columns represent running totals. The Budget Totals row produces totals for each column.</p>
            <p class="w3-margin"><b class="w3-green">TIP:</b> The Averages column calculates the monthly average spending for each budget. 
            This column can help when making monthly budget adjustments.</p>
            <img class="zoom" src="./images/budget-table.png" />
            <p class="w3-margin">Left-click the +Budget button to create new budgets. A budget is allowed for
            each expense account. When entering the Amount for the budget, provide an
            amount for one month only. The annual budgets will automatically populate with
            the correct information. Budgets may need to be adjusted from time to time but
            are an important step in gaining financial control.</p>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Income">
            <h4><u>Recording Transactions: Income</u></h4>
            <p class="w3-margin">Select the Income link from the side menu.</p>
            <img class="zoom" src="./images/income.png" />
            <p class="w3-margin">Entering income is fairly straight forward. Include all moneys obtained from
            various income generating and investing activities.</p>
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> Record loans as income using the +Loans button. 
            Loans are only reported as Cash Flow. Only the amount that is forgiven should be reported and taxed as income. 
            The amount entered for Loans will not be included in the income charts above but will be recorded and displayed
            in the approapriate accounts on the Assets page.</p>
            <p class="w3-margin">Left-click the Income panel located at the bottom of the page. To enter new
            Income left-click the +Income button.</p>            
            <p class="w3-margin"><b class="w3-green">TIP:</b> For every table, there are several options to filter table data. Each table header
            is highlighted and offers a way to alphanumerically sort rows. Notice the double
            arrows next to each header title. Left-click the title to sort the table based on
            that information. A search box and year selector will allow you to filter results for specific letters,
            words, numbers, and years. Just start typing in the filter box to begin filtering.</p>
            <p class="w3-margin"><b class="w3-green">TIP:</b> Refresh the page if the filter box does not begin filtering as you type.</p>
            <img class="zoom" src="./images/income-apy.png" />
            <p class="w3-margin">Select the +APY button to enter single or multiple APY transactions for each Asset account that has an APY
            percentage amount more than %0.00. After entering the transactions, check each amount for accuracy and adjust as
            needed.</p>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Inc-Exmpls">
            <h4><u>Recording Transactions: Income Examples</u></h4>
            <div class="w3-row">
                <div class="w3-right w3-half">
                    <img class="zoom" src="./images/inc-exmpl1.png" />
                </div>
                <div class="w3-half">
                    <p class="w3-margin"><b>Loan:</b></p>
                    <ol class="w3-margin-left">
                        <li>Left-click the +Loan button</li>
                        <li>On the Income Modal select a date and enter a description and amount</li>
                        <li>For the Category, select Loans</li>
                        <li>Choose the Account the loan was deposited into</li>
                    </ol> 
                    <p class="w3-margin"><b class="w3-green">TIP:</b> If the loan or a portion of the loan is later forgiven, reduce the amount of the 
                    initial loan entry by the amount forgiven and add the amount forgiven back into the Account where the money was deposited as income.</p>
                    <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> Loans do not appear in the Income charts as actual income but will increase the appropriate account balances 
                    and Cash Flow reports/charts.</p>
                </div>
            </div>
            <hr></hr>
            <p class="w3-margin"><b>Complex Income Example:</b></p>
                <div class="">
                    <img class="zoom" src="./images/inc-asset.png" />                
                </div>
            <div class="w3-row">            
                <div class="w3-right w3-half">
                    <img class="zoom" src="./images/inc-gain.png" />
                </div>
                <div class="w3-twothirds">                
                    <p class="w3-margin">The sale of an Asset that includes Capital Gains and depreciation recapture:</p>
                    <ul class="w3-margin-left">
                        <li>The total sale value = 13,000.00</li>
                        <li>Beginning Value = 12,500.00</li>
                        <li>Depreciation = 1,200.00</li>
                        <li>Currrent Value = 11,300.00</li>
                        <li>Depreciation Recap = 1,200.00</li>
                        <li>Capital Gains = 500.00</li>
                    </ul>
                    <ol>
                        <li>First, record a 500.00 Capital Gains Income entry for the Asset.</li>
                        <li>Next, record a 1200.00 ordinary taxable Income entry for the Depreciation Recap.</li>
                        <li>Finally, transfer the new “Cur Value” (500 + 11,300 = 11,800) of the Asset to the account the money was deposited.</li>
                    </ol>
                </div>
                <div class="">
                    <img class="zoom" src="./images/inc-entry.png" />
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-half">
                    <img class="zoom" src="./images/inc-depr.png" />
                </div>
                <div class="w3-half">
                    <img class="zoom" src="./images/inc-transfer.png" />
                </div>
            </div>            
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Expense">
            <h4><u>Recording Transactions: Expense</u></h4>
            <p class="w3-margin">Select the expense link from the side menu.</p>
            <p class="w3-margin">Left-click the Expense panel located at the bottom of the page. To enter a new
            expense left-click the Add Expense button. To enter refunds left-click the Refund
            button next to the Add Expense button.</p>
            <div class="">
                <img class="zoom" src="./images/expense-charts.png" />
            </div>
            <p class="w3-margin">All expense transactions are recorded here for all accounts. For recording the different category of expenses, a unique button 
            is available as follows: +Expense for regular purchases, +Repayment for credit card or loan payments, +Refund for reimbursments, 
            and +APR for credit or loan interest expenses.</p>
            <div class="w3-row">
                <div class="w3-half">
                    <p class="w3-margin">Be mindful when selecting the Type. Each Type option corresponds to a specific type of “Transaction”:</p>
                    <ul class="w3-margin-left">
                        <li>ATM - All cash withdraws</li>
                        <li>Bank EFT - All bank transactions such as online bill payments, purchases, and bank fees</li>
                        <li>Cash - All cash purchases</li>
                        <li>Check - Payments made by check</li>
                        <li>Credit - All credit purchases, credit interest, and charges</li>
                        <li>Debit Card - All debit card purchases</li>
                        <li>Depreciation - Any depreciation of property & equipment</li>
                        <li>Capital Loss - Any loss from investing activities</li>
                        <li>Refunds - All refunds for purchase returns</li>
                    </ul>
                </div>
                <div class="w3-half">
                    <img class="zoom" src="./images/expense-add.png" />
                </div>
            </div>              
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Exp-Exmpls">
            <h4><u>Recording Transactions: Expense Examples</u></h4>
            <p class="w3-margin">Online credit card payment from bank account:</p>
            <ol class="w3-margin-left">
                <li>Select the +Repayment button</li>
                <li>Select a date and enter a description and amount</li>
                <li>Select a credit card from Category “Expense/Liability”</li>
                <li>Select the payment account from Account “Source” and then select Bank EFT for the Type “Transaction”</li>
                <li>Click Save Payment button</li>
            </ol>
            <p class="w3-margin">Record credit card interest: 
            A listing will be available for selection in this modal for every credit card or loan that has an assigned APR value on the Liabilities page.</p>
            <ol class="w3-margin-left">
                <li>Select the +APR button</li>
                <li>Select a date </li>
                <li>Select the Credit Card / Loan account(s) from the list - multiple accounts can be selected</li>
                <li>Click Commit APR button</li>
            </ol>
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> The amount for the APR entry will be automatically calculated for the corresponding
            entry. This is intended as a convenience. Most likely the amount will need to be adjusted to reflect the actual amount. This can be acheived 
            easliy by editing the entry.</p>
            <div class="">
                <img class="zoom" src="./images/expense-btns.png" />
            </div>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Transfers">
            <h4><u>Recording Transactions: Transfers</u></h4>
            <div class="">
                <img class="zoom" src="./images/transfers.png" />
            </div>
            <p class="w3-margin">Select the Transfers link from the side menu.</p>
            <p class="w3-margin">Select the Transfer Assets panel.</p>
            <p class="w3-margin"><b class="w3-yellow">IMPORTANT:</b> Only transfers between Assets are recorded here. No other type of
            transaction should be recorded in this table.</p>
            <p class="w3-margin">Left-click the + Transfer button to enter a new asset transfer.</p>
            <p class="w3-margin">Transfers are not treated as income or expense transactions. They will only affect the balances for the associated accounts.</p>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="User">
            <h4><u>User</u></h4>
            <p class="w3-margin">Select the avatar located at the top left of the web page to access the User page.</p>
            <div clas="">
                <img class="zoom" src="./images/useracct.jpg" />
            </div>
            <p class="w3-margin">Here you can track logins, export or backup database tables, and view or undo deleted transactions for Income, Expense, and Transfer accounts.</p>
            <div clas="">
                <img class="zoom" src="./images/last7.jpg" />
            </div>
        </div>
        <div class="w3-margin w3-padding dynamic-img" id="Summary">
            <h4><u>Summary</u></h4>
            <div class="">
                <img class="zoom" src="./images/summary.png" />
            </div>
            <p class="w3-margin">Select the Summary link from the side menu.</p>
            <p class="w3-margin">The Summary page includes two panels; Income & Expense and Cash Flow. The Income & Expense panel presents a 
            printable report of all income and expenses except for liability payments (liability payments are represented in the Cash
            Flow report).</p>
            <p class="w3-margin"><b class="w3-green">TIP:</b> Both reports offer the option to select the year for the report.
            <p class="w3-margin">The Cash Flow panel presents a printable report of all cash income and
            expenses except for purchases on credit (credit purchases are represented in
            the Income & Expense report).</p>
        </div>
    </div>
</div>

<script src="./js/manual.js"></script>
<?php require_once './incld/footer.php'; ?>