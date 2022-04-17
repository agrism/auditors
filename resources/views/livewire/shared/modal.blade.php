<div>
    <div class="xxx"
         style="position:absolute;width: 300px;background-color: white;opacity: 0.8; border: 1px solid grey;position: fixed;z-index: 1000;top: 50%;left: 50%;transform: translate(-50%, -50%);border-radius: 15px;overflow: hidden;box-shadow: 2px 4px 10px grey;">
        <form wire:submit.prevent="submit">
            {!! html_entity_decode($slot) !!}
            <input type="submit" value="submit">
        </form>
    </div>
</div>