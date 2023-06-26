<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title>Send Attachment With Email</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div style="display:flex; justify-content: center; margin-top:10%;">
        <form method="POST" action="" style="width: 500px;">
            <div class="form-group">
                <input class="form-control" type="text" name="sender_name" placeholder="Your Name" required/>
            </div>
            <div class="form-group">
                <input class="form-control" type="email" name="sender_email" placeholder="Recipient's Email Address" required/>
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-control" type="radio" name="gender" id="gender" value="male">
                    <label class="form-check-label" for="inlineRadio1">male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-control" type="radio" name="gender" id="gender" value="female">
                    <label class="form-check-label" for="inlineRadio2">female</label>
                </div>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="message" placeholder="Message"></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" id="submitBtn" name="button" value="Submit" style="display:none" />
                <input class="btn btn-primary" type="submit" id="confirmBtn" name="button2" value="Confirm" />
                <input class="btn btn-primary" type="submit" id="backBtn" name="button3" value="Back" style="display:none" />
            </div>           
        </form>
        <div id="pdf" style="display: none"></div>
    </div>
    <script>
        // radio button 
        let myGender = ""

        confirmBtn.onclick = (e)=>{
            e.preventDefault()

            // content prepare
            document.querySelectorAll("input[name=gender]").forEach(element => {
                if( element.checked === true ) myGender = element.value
            })
            pdf.innerHTML = `
                <ul>
                    <li>
                        sender_name : 
                        ${document.querySelector("input[name=sender_name]").value}
                    </li>
                    <li>
                        sender_email : 
                        ${document.querySelector("input[name=sender_email]").value}
                    </li>
                    <li>
                        gender : 
                        ${myGender}
                    </li>
                    <li>
                        message : 
                        ${document.querySelector("textarea[name=message]").value.replace(/\r?\n/g, '<br />')}
                    </li>
                </ul>
            `
            
            document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: none; background: gainsboro;` )
            confirmBtn.style.display = "none"
            submitBtn.style.display = "block"
            backBtn.style.display = "block"
        }
        backBtn.onclick = (e)=>{
            e.preventDefault()

            document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: visible; background: transparent;` )
            confirmBtn.style.display = "block"
            submitBtn.style.display = "none"
            backBtn.style.display = "none"
        }
        submitBtn.onclick = (e)=>{
            e.preventDefault()

            // form remove
            document.querySelector('form').style.display = "none"
            pdf.style.display = "block"
            
            // pdf execute
            opt = {
                margin: [5,0,5,0],
                html2canvas:  { 
                    scale: 1.5,
                    y: 0,
                    x: 0,
                    scrollY: 0,
                    scrollX: 0,
                    windowWidth: 800,
                },
                filename:     'myfile.pdf',
            }
            // html2pdf().set(opt).from(pdf).save()
            html2pdf().set(opt).from(pdf).outputPdf().then(function(pdf) {
                const formData = new FormData()
                formData.append(
                    'sender_name', 
                    document.querySelector("input[name=sender_name]").value
                )
                formData.append(
                    'sender_email', 
                    document.querySelector("input[name=sender_email]").value
                )
                formData.append(
                    'gender', 
                    myGender
                )
                formData.append(
                    'message', 
                    document.querySelector("textarea[name=message]").value
                )
                formData.append(
                    'pdf', 
                    btoa(pdf)
                )

                fetch(`${window.location.href}process.php`, {
                    method: 'post',
                    body: formData
                })
                .then(response => response.text())
                .then(body => {
                    console.log(body)
                })

            })

            setTimeout(() => {
                pdf.style.display = "none"
                document.querySelector('form').style.display = "block"
                document.querySelector('form').reset()
                document.querySelectorAll('.form-control').forEach( e => e.style.cssText = `pointer-events: visible; background: transparent;` )
            }, 1000)
        }
    </script>
</body>
</html>