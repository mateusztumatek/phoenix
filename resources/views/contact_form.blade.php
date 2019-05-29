<div class="container contact-form">
    <div class="contact-image">
        <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form method="post" action="{{route('send.email')}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Your Name *"  required />
                </div>
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="Your Email " required />
                </div>
                <div class="form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Your Phone Number *" />
                </div>
                <div class="form-group">
                    <input type="submit" name="btnSubmit" class="btn my-button-border" value="Wyślij wiadomość" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <textarea name="message" class="form-control" placeholder="Your Message *" style="width: 100%; height: 150px;" required></textarea>
                </div>
            </div>
        </div>
    </form>
</div>